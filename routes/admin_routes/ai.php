<?php

/*
|--------------------------------------------------------------------------
| AI Providers, Cost & Performance & Job Ingestion Pipeline Admin Routes
|--------------------------------------------------------------------------
*/

// AI Providers Management
Route::get('ai-providers', array_merge(['uses' => 'Admin\AIProviderController@index'], $all_users))->name('admin.ai.providers');
Route::get('ai-providers/create', array_merge(['uses' => 'Admin\AIProviderController@create'], $all_users))->name('admin.ai.providers.create');
Route::post('ai-providers/store', array_merge(['uses' => 'Admin\AIProviderController@store'], $all_users))->name('admin.ai.providers.store');
Route::get('ai-providers/{id}/edit', array_merge(['uses' => 'Admin\AIProviderController@edit'], $all_users))->name('admin.ai.providers.edit');
Route::put('ai-providers/{id}/update', array_merge(['uses' => 'Admin\AIProviderController@update'], $all_users))->name('admin.ai.providers.update');
Route::post('ai-providers/{id}/set-active', array_merge(['uses' => 'Admin\AIProviderController@setActive'], $all_users))->name('admin.ai.providers.set_active');
Route::post('ai-providers/{id}/toggle-status', array_merge(['uses' => 'Admin\AIProviderController@toggleStatus'], $all_users))->name('admin.ai.providers.toggle_status');
Route::post('ai-providers/{id}/test-connection', array_merge(['uses' => 'Admin\AIProviderController@testConnection'], $all_users))->name('admin.ai.providers.test_connection');
Route::delete('ai-providers/{id}/delete', array_merge(['uses' => 'Admin\AIProviderController@destroy'], $all_users))->name('admin.ai.providers.destroy');

// AI Cost & Performance Dashboard
Route::get('ai-cost-performance', array_merge(['uses' => 'Admin\AICostPerformanceController@index'], $all_users))->name('admin.ai.cost_performance');

// AI Automated Job Pipeline
Route::get('ai-job-pipeline', array_merge(['uses' => 'Admin\AIJobPipelineController@index'], $all_users))->name('admin.ai.pipeline');
Route::post('ai-job-pipeline/ingest', array_merge(['uses' => 'Admin\AIJobPipelineController@ingestRawJob'], $all_users))->name('admin.ai.pipeline.ingest');
Route::post('ai-job-pipeline/enrich/{id}', array_merge(['uses' => 'Admin\AIJobPipelineController@enrichSingle'], $all_users))->name('admin.ai.pipeline.enrich');
Route::post('ai-job-pipeline/publish/{id}', array_merge(['uses' => 'Admin\AIJobPipelineController@publishSingle'], $all_users))->name('admin.ai.pipeline.publish');
Route::post('ai-job-pipeline/fetch-adzuna', array_merge(['uses' => 'Admin\AIJobPipelineController@fetchAdzunaJobs'], $all_users))->name('admin.ai.pipeline.fetch_adzuna');
Route::put('ai-job-pipeline/raw/{id}/update', array_merge(['uses' => 'Admin\AIJobPipelineController@updateRawJob'], $all_users))->name('admin.ai.pipeline.raw.update');
Route::delete('ai-job-pipeline/raw/{id}/delete', array_merge(['uses' => 'Admin\AIJobPipelineController@deleteRawJob'], $all_users))->name('admin.ai.pipeline.raw.delete');
Route::post('ai-job-pipeline/raw/bulk-delete', array_merge(['uses' => 'Admin\AIJobPipelineController@bulkDeleteRawJobs'], $all_users))->name('admin.ai.pipeline.raw.bulk_delete');
Route::post('ai-job-pipeline/seed-samples', array_merge(['uses' => 'Admin\AIJobPipelineController@seedSampleJobs'], $all_users))->name('admin.ai.pipeline.seed_samples');
Route::post('ai-job-pipeline/settings/update', array_merge(['uses' => 'Admin\AIJobPipelineController@updateSettings'], $all_users))->name('admin.ai.pipeline.update_settings');

// AI Job Import & Auto-Fill (Image + Raw Text)
Route::get('ai-job-import', array_merge(['uses' => 'Admin\AIJobImportController@index'], $all_users))->name('admin.ai.job_import');
Route::post('ai-job-import/extract', array_merge(['uses' => 'Admin\AIJobImportController@extract'], $all_users))->name('admin.ai.job_import.extract');
Route::post('ai-job-import/save', array_merge(['uses' => 'Admin\AIJobImportController@saveJob'], $all_users))->name('admin.ai.job_import.save');
