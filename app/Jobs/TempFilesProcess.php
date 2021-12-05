<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use App\Models\Clients;
use Carbon\Carbon;

class TempFilesProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $tries = 3;
    public $backoff = 3;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $client) {
            // Format Birhday date into Y-m-d
            $client = Arr::set($client, 'date_of_birth', date('Y-m-d', strtotime($client['date_of_birth'])));
            $clientExist = Clients::where('name', $client['name'])->where('date_of_birth', $client['date_of_birth'])->exists();

            if (!$clientExist) {
                $clientAge = Carbon::parse($client['date_of_birth'])->diff(Carbon::now())->y;
                $creditCardDetails = Arr::get($client, 'credit_card');

                // Bonus point
                $cardWithThreeSameConsecutiveDigit = preg_match('/(.)\\1{2}/', $creditCardDetails['number']);
                $validAgeRange = $clientAge >= 18 && $clientAge <= 65 || $clientAge == null;


                if ($validAgeRange && $cardWithThreeSameConsecutiveDigit) {
                    $clientInfo = Arr::except($client, 'credit_card');
                    $createdClient = Clients::create($clientInfo);
                    $createdClient->CreditCardAttributes()->create($creditCardDetails);
                }
            }
        };
    }
    public function failed()
    {
        Artisan::call('queue:retry all');
    }
}
