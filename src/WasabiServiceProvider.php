<?php

namespace Luudv\Wasabi;

use Storage;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class WasabiServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Storage::extend('wasabi', function ($app, $config) {
			$credentials = new Credentials($config['key'], $config['secret']);
			$conf = [
				'endpoint' => "https://" . $config['bucket'] . ".s3." . $config['region'] . ".wasabisys.com",
				'bucket_endpoint' => true,
				'credentials' => $credentials,
				'region' => $config['region'],
				'version' => 'latest',
			];

			$client = new S3Client($conf);

			$adapter = new AwsS3V3Adapter($client, $config['bucket'], $config['root']);

			$filesystem = new Filesystem($adapter);

			return $filesystem;
		});
	}
}
