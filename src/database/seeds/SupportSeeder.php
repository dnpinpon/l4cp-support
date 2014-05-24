<?php

class SupportSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        $this->call('ActionsTableSeeder');
    }
}