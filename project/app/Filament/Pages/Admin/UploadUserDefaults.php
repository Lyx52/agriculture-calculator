<?php

namespace App\Filament\Pages\Admin;

use App\Console\Commands\ImportUserDefaults;
use App\Enums\CropProtectionColumn;
use App\Enums\DefaultImports;
use App\Enums\FertilizerColumn;
use App\Jobs\ImportDefaultModelsJob;
use App\Jobs\ImportUserDefaultsJob;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class UploadUserDefaults extends Page implements HasForms
{
    use InteractsWithForms;
    protected string $view = 'filament.pages.admin.upload-user-defaults';
    protected static ?string $breadcrumb = 'Lietotāja noklusētie dati';
    protected static ?string $navigationLabel = 'Lietotāja noklusētie dati';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public array $data = [
        'column_options' => [],
        'header_row' => 1,
        'uploaded_file' => null,
        'default_type' => null,
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function create() {
        $filePath = $this->data['uploaded_file'];
        $headerRow = $this->data['header_row'] ?? 1;
        $defaultType = $this->data['default_type'] ?? null;
        $columns = $this->data['columns'];
        if (empty($filePath) || empty($defaultType)) {
            return;
        }

        ImportDefaultModelsJob::dispatch($filePath, $headerRow, $defaultType, $columns);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->schema([
            Section::make('Augšupielādēt noklusētās vērtības')->schema([
                TextInput::make('header_row')
                    ->label('Galvenes rinda')
                    ->numeric()
                    ->default(1)
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get) => $this->updateHeaderColumns($get, $set))
                    ->suffixAction(function (Get $get) {
                        $fileName = match($get('default_type')) {
                            DefaultImports::CROP_PROTECTION => 'augu_aizsardzibas_lidzekli.xlsx',
                            DefaultImports::FERTILIZERS => 'mineralmeslojums.xlsx',
                            DefaultImports::CROP_SPECIES => 'skirnu_katalogs.xlsx',
                            default => null
                        };
                        if (empty($fileName)) return null;

                        return Action::make('downloadFile')
                            ->label('Lejupielādēt paraugu')
                            ->icon('heroicon-m-arrow-down-tray')
                            ->action(fn (UploadUserDefaults $livewire) => $livewire->downloadFile($fileName));
                    }),

                FileUpload::make('defaults_file')
                    ->label('Excel fails')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state instanceof TemporaryUploadedFile) {
                            $set('uploaded_file', $state->path());
                        }

                        $this->updateHeaderColumns($get, $set);
                    }),

                Select::make('default_type')
                    ->live()
                    ->native(false)
                    ->label('Noklusētās vērtības tips')
                    ->options(DefaultImports::class),

                Fieldset::make('Augu aizsardzības kolonnas')
                    ->statePath('columns')
                    ->columns(1)
                    ->live()
                    ->visible(fn(Get $get) => $get('default_type') == DefaultImports::CROP_PROTECTION)
                    ->schema($this->createColumnBuilderSchema(CropProtectionColumn::class)),

                Fieldset::make('Skirnes kataloga kolonnas')
                    ->statePath('columns')
                    ->columns(1)
                    ->live()
                    ->visible(fn(Get $get) => $get('default_type') == DefaultImports::CROP_SPECIES)
                    ->schema($this->createColumnBuilderSchema(CropProtectionColumn::class)),

                Fieldset::make('Aizsardzības mineralmēslojumU kolonnas')
                    ->statePath('columns')
                    ->columns(1)
                    ->live()
                    ->visible(fn(Get $get) => $get('default_type') == DefaultImports::FERTILIZERS)
                    ->schema($this->createColumnBuilderSchema(FertilizerColumn::class)),
            ])
        ]);
    }

    /**
     * @param class-string<UnitEnum|HasLabel> $columnEnumClass
     * @return array
     */
    private function createColumnBuilderSchema(string $columnEnumClass): array {
        return collect($columnEnumClass::cases())->map(function ($column) {
            return Grid::make(3)
                ->statePath($column->value)
                ->schema([
                    TextEntry::make('name')
                        ->hiddenLabel()
                        ->getStateUsing(fn() => $column->getLabel()),
                    Select::make('mapped_to_column')
                        ->hiddenLabel()
                        ->native(false)
                        ->options(fn (Get $get) => $get('../../column_options'))
                        ->live(),
                    TextInput::make('default_value')
                        ->hiddenLabel()
                        ->placeholder('Noklusētā vērtība')
                ]);
        })->toArray();
    }

    public function downloadFile(string $fileName): StreamedResponse
    {
        return Storage::disk('local')->download($fileName);
    }

    public function updateHeaderColumns(Get $get, Set $set) {
        $filePath = $get('uploaded_file');
        if (empty($filePath)) {
            $set('column_options', []);
            return;
        }

        $reader = new Reader();
        $reader->open($filePath);
        $sheet = $reader->getSheetIterator()->current();
        $header = null;
        $headerRow = $get('header_row') ?? 1;
        foreach ($sheet->getRowIterator() as $idx => $row) {
            if ($idx == $headerRow) {
                $header = array_filter($row->toArray());
                break;
            }
        }

        $set('column_options', $header ?? []);
    }
}
