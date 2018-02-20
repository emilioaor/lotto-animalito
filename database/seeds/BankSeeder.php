<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = new Bank();
        $bank->name = 'Banesco';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco de Venezuela';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'BOD';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'BBVA Provincial';
        $bank->i_have = true;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Mercantil';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Exterior';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'BNC';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bancaribe';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Venezolano de Crédito';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bancrecer';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Fondo Común';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banplus';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco del Tesoro';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Plaza';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Caroní';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banfanb';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'DEL SUR';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bicentenario Banco Universal​';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Activo';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = '100% Banco';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Mi Banco';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Agrícola de Venezuela';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Sofitasa';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Instituto Municipal de Crédito Popular (IMCP)';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bancamiga';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bancoex';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Bangente';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco de Exportación y Comercio';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Banco Internacional de Desarrollo';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Novo Banco';
        $bank->i_have = false;
        $bank->save();

        $bank = new Bank();
        $bank->name = 'Citibank';
        $bank->i_have = false;
        $bank->save();

    }
}
