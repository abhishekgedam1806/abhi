<?php



/*

  |--------------------------------------------------------------------------

  | Web Routes

  |--------------------------------------------------------------------------

  |

  | Here is where you can register web routes for your application. These

  | routes are loaded by the RouteServiceProvider within a group which

  | contains the "web" middleware group. Now create something great!

  |

 */

$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'front_routes' . DIRECTORY_SEPARATOR;

/* * ******** IndexController ************ */

Route::get('/', 'IndexController@index')->name('index');
Route::get('manifest.json', function () {
    return response()->file(public_path('manifest.json'), ['Content-Type' => 'application/manifest+json']);
});
Route::get('sw.js', function () {
    return response()->file(public_path('sw.js'), ['Content-Type' => 'application/javascript']);
});
Route::get('pricing', 'PricingController@index')->name('pricing');
Route::post('set-locale', 'IndexController@setLocale')->name('set.locale');

/* * ******** HomeController ************ */

Route::get('home', 'HomeController@index')->name('home');

/* * ******** TypeAheadController ******* */

Route::get('typeahead-currency_codes', 'TypeAheadController@typeAheadCurrencyCodes')->name('typeahead.currency_codes');

/* * ******** FaqController ******* */

Route::get('faq', 'FaqController@index')->name('faq');

/* * ******** CronController ******* */

Route::get('check-package-validity', 'CronController@checkPackageValidity');

/* * ******** Verification ******* */

Route::get('email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');

Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::get('company-email-verification/error', 'Company\Auth\RegisterController@getVerificationError')->name('company.email-verification.error');

Route::get('company-email-verification/check/{token}', 'Company\Auth\RegisterController@getVerification')->name('company.email-verification.check');

/* * ***************************** */

// Sociallite Start

// OAuth Routes

Route::get('login/jobseeker/{provider}', 'Auth\LoginController@redirectToProvider');

Route::get('login/jobseeker/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('login/employer/{provider}', 'Company\Auth\LoginController@redirectToProvider');

Route::get('login/employer/{provider}/callback', 'Company\Auth\LoginController@handleProviderCallback');

// Sociallite End

/* * ***************************** */

Route::post('tinymce-image_upload-front', 'TinyMceController@uploadImage')->name('tinymce.image_upload.front');



Route::get('cronjob/send-alerts', 'AlertCronController@index')->name('send-alerts');



Route::post('subscribe-newsletter', 'SubscriptionController@getSubscription')->name('subscribe.newsletter');



/* * ******** OrderController ************ */

include_once($real_path . 'order.php');

/* * ******** CmsController ************ */

include_once($real_path . 'cms.php');

/* * ******** JobController ************ */

include_once($real_path . 'job.php');

/* * ******** ContactController ************ */

include_once($real_path . 'contact.php');

/* * ******** CompanyController ************ */

include_once($real_path . 'company.php');

/* * ******** AjaxController ************ */

include_once($real_path . 'ajax.php');

/* * ******** UserController ************ */

include_once($real_path . 'site_user.php');

/* * ******** User Auth ************ */

Auth::routes();
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout');

/* * ******** Company Auth ************ */

include_once($real_path . 'company_auth.php');

/* * ******** Admin Auth ************ */

include_once($real_path . 'admin_auth.php');

/* * ******** Email OTP Login & Registration Routes ************ */
Route::post('auth/otp/send', 'Auth\OtpLoginController@sendOtp')->name('otp.send');
Route::post('auth/otp/verify', 'Auth\OtpLoginController@verifyOtp')->name('otp.verify');
Route::post('auth/register/candidate', 'Auth\OtpLoginController@registerCandidate')->name('otp.register.candidate');
Route::post('auth/register/employer', 'Auth\OtpLoginController@registerEmployer')->name('otp.register.employer');
Route::post('auth/register/business', 'Auth\OtpLoginController@registerBusiness')->name('otp.register.business');





Route::get('blog', 'BlogController@index')->name('blogs');
Route::get('blogs', 'BlogController@index');

Route::get('blog/search', 'BlogController@search')->name('blog-search');

Route::get('blog/{slug}', 'BlogController@details')->name('blog-detail');

Route::get('/blog/category/{blog}', 'BlogController@categories')->name('blog-category');

Route::get('/company-change-message-status', 'CompanyMessagesController@change_message_status')->name('company-change-message-status');
Route::get('/seeker-change-message-status', 'Job\SeekerSendController@change_message_status')->name('seeker-change-message-status');

/* * ********* Separate SEO XML Sitemaps *********** */
Route::get('/sitemap.xml', 'SitemapController@index')->name('sitemap.index');
Route::get('/sitemap-pages.xml', 'SitemapController@pages')->name('sitemap.pages');
Route::get('/sitemap-jobs.xml', 'SitemapController@jobs')->name('sitemap.jobs');
Route::get('/sitemap-companies.xml', 'SitemapController@companies')->name('sitemap.companies');
Route::get('/sitemap-businesses.xml', 'SitemapController@businesses')->name('sitemap.businesses');
Route::get('/sitemap', 'SitemapController@index');
Route::get('/sitemap/companies', 'SitemapController@companies');



/* * ******** BusinessController ************ */
include_once($real_path . 'business.php');

/* * ******** Real-Time In-App Notification System ************ */
Route::get('/notifications/fetch', 'NotificationController@fetch')->name('notification.fetch');
Route::get('/notifications/read/{id}', 'NotificationController@readAndRedirect')->name('notification.read');
Route::post('/notifications/mark-all-read', 'NotificationController@markAllAsRead')->name('notification.mark-all-read');
Route::get('/notifications/all', 'NotificationController@allNotifications')->name('notification.all');

/* * ******** Candidate Profile AI Suite ************ */
Route::post('/candidate/ai/summary-generate', 'User\CandidateAIController@generateSummary')->name('candidate.ai.summary');
Route::post('/candidate/ai/recommend-skills', 'User\CandidateAIController@recommendSkills')->name('candidate.ai.recommend_skills');
Route::post('/candidate/ai/add-skill', 'User\CandidateAIController@addRecommendedSkill')->name('candidate.ai.add_skill');

/* * ******** WhatsApp User & Company Notification Preferences ************ */
Route::get('/whatsapp/preferences', 'User\WhatsAppPreferenceController@getPreferences')->name('whatsapp.preferences.get');
Route::post('/whatsapp/preferences', 'User\WhatsAppPreferenceController@updatePreferences')->name('whatsapp.preferences.update');
Route::post('/whatsapp/preferences/send-otp', 'User\WhatsAppPreferenceController@sendVerificationOtp')->name('whatsapp.preferences.send_otp');
Route::post('/whatsapp/preferences/verify-otp', 'User\WhatsAppPreferenceController@verifyOtp')->name('whatsapp.preferences.verify_otp');


