<?php

class ActionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('ticket_actions')->delete();

        $user_id = User::first()->id;

        DB::table('ticket_actions')->insert( array(
            array(
                'name'      => 'open'
            ),
            array(
                'name'      => 'close'
            ),
            array(
                'name'      => 'reply'
            ),
            array(
                'name'      => 'edit'
            )
		)
        );
    }

}
