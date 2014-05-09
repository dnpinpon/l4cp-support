<?php namespace Gcphost\L4cpSupport;

use Illuminate\Support\ServiceProvider;
use View, Search, CronWrapper, Setting;

class L4cpSupportServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the routes.
	 *
	 * @return void
	 */
	public function boot()
	{

		$this->package('Gcphost/l4cp-support');

		include __DIR__.'/../../routes.php';

		$this->extend();

	}

	/**
	 * Extend the default panel.
	 *
	 * @return void
	 */
	private function extend(){
		CronWrapper::Add('Support::Cron');

		Search::AddTable('ticket', array('title','message'), array('id' => array('method'=>'link', 'action'=>'admin/support/?/thread')));
		Search::AddTable('ticket_replies', array('ticket_id','content'), array('ticket_id' => array('method'=>'link', 'action'=>'admin/support/?/thread')));

		View::composer('*admin/layouts/default', function($view)    
		{    
			$view->nest('test','l4cp-support::navigation');
		});


	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
		
	{

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{

		return array();
	}




}
