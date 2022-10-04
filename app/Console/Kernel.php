<?php

namespace App\Console;

use App\Http\Controllers\AcaoController;
use App\Mail\PaymentMail;
use App\Models\Assinatura;
use App\Models\Caixa;
use App\Models\Compra;
use App\Models\Credito;
use App\Models\Extrato;
use App\Models\Plano;
use App\Models\Soma;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (AcaoController $acaoController) {
            //  $faturas = Compra::where('ativo', 0)->get();

            //   $faturas = Compra::where('ativo', 0)->orderBy('id', 'asc')->where('pay_address', "!=", 'NULL')->get();

            // dd($faturas);


            $faturas = Compra::where('ativo', 0)->orderBy('id', 'asc')->where('pay_address', "!=", 'NULL')->get();
            //dd($faturas);

            foreach ($faturas as $fatura) {
                //dd($fatura);




                $cobranca = $fatura->consultahash();

                if ($cobranca['payment_status'] == "finished") {
                    $fatura->fill(['ativo' => 1]);
                    $fatura->save();
                    $details = [
                        'title' => 'Corfirm Payment',
                        'url' => url('dashboard')
                    ];

                    Mail::to($fatura->user->email)->send(new PaymentMail($details));
                    $grava = [
                        'descricao' => 'Recebido da mensalidade do ' . $fatura->user->name,
                        'valor' => $fatura->plano->valor,
                        'tipo' => 1,
                        'user_id' => $fatura->user->id,
                    ];

                    Caixa::create($grava);

                    if (!empty($fatura->user->quarto())) {
                        $direto = $acaoController::calculorenda($fatura, 4);
                    }
                    if (!empty($fatura->user->terceiro())) {
                        $direto = $acaoController::calculorenda($fatura, 3);
                    }
                    if (!empty($fatura->user->segundo())) {
                        $direto = $acaoController::calculorenda($fatura, 2);
                    }
                    if (!empty($fatura->user->primeiro())) {
                        $direto = $acaoController::calculorenda($fatura, 1);
                    }
                    if (!empty($fatura->user->direto())) {
                        $direto = $acaoController::calculorenda($fatura, 0);
                    }
                    //  return $direto;

                }
            }
        })->everyFiveMinutes();






        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
