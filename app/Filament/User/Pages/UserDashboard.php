<?php

namespace App\Filament\User\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Pages\Page;
use App\Models\JournalStatus;
use App\Models\Journal;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;


class UserDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    public $nama;
    public $status;
    public $tanggal;

    public $waktu_mulai;

    public $waktu_selesai;
    public $pekerjaan;
    public $keterangan;

    public $prevJournal;


    protected static string $model = 'App\Models\Journal';
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.user.pages.user-dashboard';

    protected static ?string $slug = 'dashboard';

    protected static ?string $title = 'Beranda';

    public function mount()
    {
        $prevJournal = Journal::where('user_id', auth()->id())->whereDate('created_at', Carbon::today())->latest()->first();
        if ($prevJournal) {
            $this->waktu_mulai = $prevJournal->waktu_selesai;
            $this->waktu_selesai = $prevJournal->waktu_selesai;
            $this->status = $prevJournal->status_id;
        } else {
            $this->waktu_mulai = '07:00';
            $this->waktu_selesai = $this->waktu_mulai;
        }
        $this->nama = ''; // Initialize the property
        $this->tanggal = now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $this->pekerjaan = ''; // Initialize the property
        $this->keterangan = ''; // Initialize the property
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::Make('Jurnal Harian Tata Usaha')->description(auth()->user()->name)
                    ->schema([
                        TextInput::make('tanggal')->disableLabel()->disabled()->prefix('Hari, Tanggal'),
                        Select::make('status')
                            ->options(JournalStatus::query()->pluck('nama', 'kode'))->prefix('Kehadiran')->required()->disableLabel()->markAsRequired(false)->id('status'),
                        Fieldset::make('Waktu')->schema([
                            TimePicker::make('waktu_mulai')->label('Mulai')->required()->required()->markAsRequired(false)->disabled(),
                            TimePicker::make('waktu_selesai')->label('Selesai')->required()->required()->markAsRequired(false)
                        ])->columns(columns: 2)->extraAttributes(['class' => 'to-be-hidden-1']),
                        Textarea::make('pekerjaan')->label('Pekerjaan')->requiredIf('status', 'HADIR')->placeholder('Pekerjaan yang dilakukan')->markAsRequired(false)->extraAttributes(['class' => 'to-be-hidden-2']),
                        Textarea::make('keterangan')->label('Keterangan')->required()->placeholder('Keterangan tambahan')->markAsRequired(false)->rows(3),

                    ])->footerActions([
                            Action::make('save')->label('Simpan')->button()->action(fn() => $this->save()),
                        ]),
            ]);
    }
    public function save()
    {
        if ($this->waktu_selesai < $this->waktu_mulai) {
            $this->addError('waktu_selesai', 'Waktu selesai harus lebih besar dari waktu mulai');
            return;
        }
        $this->validate([
            'status' => 'required',
            'waktu_mulai' => 'required_if:status,HADIR',
            'waktu_selesai' => 'required_if:status,HADIR',
            'pekerjaan' => 'required_if:status,HADIR',
            'keterangan' => 'required',
        ], [
            'status.required' => 'Status kehadiran harus diisi.',
            'waktu_mulai.required_if' => 'Waktu mulai harus diisi jika status HADIR.',
            'waktu_selesai.required_if' => 'Waktu selesai harus diisi jika status HADIR.',
            'pekerjaan.required_if' => 'pekerjaan harus diisi jika status HADIR.',
            'keterangan.required' => 'Keterangan harus diisi.',
        ]);
        Journal::create([
            'user_id' => auth()->id(),
            'status_id' => $this->status,
            'waktu_mulai' => date('H:i', strtotime($this->waktu_mulai)),
            'waktu_selesai' => date('H:i', strtotime($this->waktu_selesai)),
            'pekerjaan' => $this->pekerjaan,
            'keterangan' => $this->keterangan,
        ]);
        Notification::make()
            ->title('Jurnal berhasil disimpan')->success()
            ->send();
        $this->mount();

    }
}
