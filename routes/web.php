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

/* Site OLD */
Route::get('/v1', function () {
	return view('site_old/pages/home');
});
Route::get('/inovacao', function () {
	return view('site_old/pages/inovacao');
});
Route::get('/qualificacao', function () {
	return view('site_old/pages/qualificacao');
});
Route::get('/internacionalizacao', function () {
	return view('site_old/pages/internacionalizacao');
});
Route::get('/ppq', function () {
	return view('site_old/pages/ppq');
});
Route::get('/passatempo', function () {
	return view('site_old/pages/passatempo');
});
Route::get('/language/{lang}', 'Site_old\Language@getLang')->name('languageGet');
Route::post('/language', 'Site_old\Language@postLang')->name('languagePost');
Route::post('/form-contacto', 'Site_old\Home@postContacto')->name('FormContactoPost');

/*ASSINATURAS*/
Route::get('/overseas', 'Site_v2\Home@assinaturaOverseas')->name('overseasPage');

/* Site V2 */
Route::get('/', 'Site_v2\Home@index')->name('homePageV2');
Route::get('/universal-{tipo}', 'Site_v2\Home@pageProduct')->name('productPageV2');
Route::get('/page-universal', 'Site_v2\Home@pageUniversal')->name('universalPageV2');

/* download de automatico de ficheiros  */
Route::get('/communication/download/{id}', 'Backoffice\Communication@downloadFile')->name('downloadPage');

// area publica comunicacao 
Route::get('/communication/public', 'Backoffice\Communication@publicArea')->name('commPublicPage');


Route::get('/innovation', 'Site_v2\Home@pageInnovation')->name('innovationPageV2');
Route::get('/qualification', 'Site_v2\Home@pageQualification')->name('qualificationPageV2');
Route::get('/internationalization', 'Site_v2\Home@pageInternationalization')->name('internationalizationPageV2');
Route::get('/terms', 'Site_v2\Home@pageTerms')->name('termsPageV2');
Route::get('/terms-security', 'Site_v2\Home@pageSecurity')->name('securityPageV2');
Route::get('/terms-privacy', 'Site_v2\Home@pagePrivacy')->name('privacyPageV2');
Route::get('/terms-cookies', 'Site_v2\Home@pageCookies')->name('cookiesPageV2');
Route::get('/disclaimer', 'Site_v2\Home@avisoLegal')->name('avisoLegalV2');

Route::get('/pastime', 'Site_v2\Pastime@index')->name('pastimePageV2');
Route::get('/regulation/{id}', 'Site_v2\Pastime@pageRegulation')->name('regulationPageV2');
Route::get('/questionCheese', 'Site_v2\Pastime@pageQuestionCheese')->name('questionCheesePageV2');

Route::get('/codes', 'Site_v2\Codes@index')->name('codesPageV2');
Route::get('/premium', 'Site_v2\Premium@index')->name('premiumPageV2');
Route::get('/premium/{id}', 'Site_v2\Premium@infoPremium')->name('infoPremiumPageV2');
Route::get('/buy-points', 'Site_v2\Premium@buyPoints')->name('buyPointsV2');

Route::get('/contacts', 'Site_v2\Contacts@index')->name('contactsPageV2');
Route::get('/login', 'Site_v2\Login@index')->name('loginPageV2');
Route::get('/form-client', 'Site_v2\Login@formRegisterClient')->name('formRegisterClientPageV2');
Route::get('/form-seller', 'Site_v2\Login@formRegisterSeller')->name('formRegisterSellerPageV2');
Route::get('/restore-password/{token}', 'Site_v2\Login@restorePassword')->name('restorePasswordPageV2');
Route::get('/validate/{token}', 'Site_v2\Login@newAccountGestor')->name('newAccountGestorV2');

Route::get('/language-v2/{lang}', 'Site_v2\Language@getLang')->name('languageGetV2');
Route::post('/language-v2', 'Site_v2\Language@postLang')->name('languagePostV2');

Route::post('/send-contact', 'Site_v2\Contacts@sendcontact')->name('sendcontactPost');
Route::post('/subscrever-site', 'Site_v2\Home@subscreveSite')->name('subscreverPost');
Route::post('/register-client', 'Site_v2\Login@sendRegisterClient')->name('registerClientPost');
Route::post('/register-seller', 'Site_v2\Login@sendRegisterSeller')->name('registerSellerPost');

Route::post('/form-login', 'Site_v2\Login@formLogin')->name('formLoginPost');
Route::post('/send-email-pass', 'Site_v2\Login@sendEmailPassword')->name('sendEmailPassPost');
Route::post('/restore-pass', 'Site_v2\Login@restorePasswordForm')->name('restorePasswordPost');
Route::get('/logout-v2', 'Site_v2\Login@logout')->name('logoutPost');
Route::post('/resend-validation', 'Site_v2\Login@resendValidation')->name('resendValidationPost');
Route::get('/validate-account-client/{token}', 'Site_v2\Login@validateAccount')->name('validateAccountPageV2');
Route::get('/account-pending', 'Site_v2\Login@pagePending')->name('pendingPageV2');

//Culinaria
Route::get('/culinariapt', 'Site_v2\Culinaria@index')->name('culinariaPageV2');
Route::post('/culinariapt-codigo', 'Site_v2\Culinaria@formCodigo')->name('culinariaCodigoPost');
Route::post('/culinariapt-post', 'Site_v2\Culinaria@form')->name('sendEncCulinariaPost');

//Produto Gourmet
Route::get('/gourmet-{tipo}', 'Site_v2\ProductGourmet@index')->name('productGourmetPageV2');

/* Area Utilizador - Client */
Route::group(['middleware' => ['Client']], function () {
	Route::get('/area-reserved', 'Client\Home@index')->name('areaReservedV2');
	Route::post('/insert-code', 'Client\Home@insertCodes')->name('insertCodesPostV2');
	Route::get('/area-reserved-data', 'Client\Data@index')->name('areaReservedDataV2');
	Route::get('/area-reserved-address', 'Client\Data@adress')->name('areaReservedAdressV2');
	Route::get('/edit-address-billing', 'Client\Data@editAdressBilling')->name('editAdressBillingV2');
	Route::get('/edit-address-delivery', 'Client\Data@editAdressDelivery')->name('editAdressDeliveryV2');
	Route::get('/regulation', 'Client\Data@showRegulation')->name('showRegulationV2');
	Route::get('/history-points', 'Client\Points@index')->name('historyPointsV2');
	Route::get('/history-premium', 'Client\Premium@index')->name('historyPremiumV2');
	Route::post('/update-data', 'Client\Data@updateData')->name('updateDataPostV2');
	Route::post('/upload-photo', 'Client\Data@uploadPhoto')->name('uploadPhotoPostV2');
	Route::post('/delete-photo-user', 'Client\Data@deletePhoto')->name('deletePhotoPostV2');
	Route::post('/update-address-billing', 'Client\Data@updateAdressB')->name('updateAdressBPostV2');
	Route::post('/update-address-delivery', 'Client\Data@updateAdressD')->name('updateAdressDPostV2');
	Route::post('/ask-info-premium', 'Client\Data@askInfoPremium')->name('askInfoPostV2');
	Route::post('/delete-account', 'Client\Data@deleteAccount')->name('deleteAccountPostV2');
	Route::post('/resend-email', 'Client\Data@resendEmail')->name('resendEmailPostV2');
	Route::post('/cancel-email', 'Client\Data@cancelEmailValition')->name('cancelEmailPostV2');
	Route::get('/validate-email/{token}', 'Client\Data@valiteResendEmail')->name('valiteResendEmailV2');
	Route::get('/cart', 'Client\Cart@index')->name('cartPageV2');
	Route::post('/add-premium-cart', 'Client\Cart@addPremiumCart')->name('addPremioPostV2');
	Route::get('/cart-sucess', 'Client\Cart@cartSucess')->name('cartSucessPageV2');
	Route::post('/update-qtd-premium', 'Client\Cart@updateQtdPremium')->name('updateQtdPostV2');
	Route::get('/cart-billing', 'Client\Cart@cartBilling')->name('cartBillingPageV2');
	Route::post('/add-billing', 'Client\Cart@addBilling')->name('addBillingPostV2');
	Route::get('/cart-summary', 'Client\Cart@cartSummary')->name('cartSummaryPageV2');
	Route::post('/delete-premium', 'Client\Cart@deletePremium')->name('deletePremiumPostV2');
});




/* Area Comerciante - Seller */
Route::group(['middleware' => ['Seller']], function () {
	Route::get('/dashboard', 'Seller\Dashboard@index')->name('dashboardV2');
	Route::get('/personal-data', 'Seller\PersonalData@index')->name('personalDataV2');
	Route::get('/company-data', 'Seller\CompanyData@index')->name('companyDataV2');
	Route::get('/legal-representative', 'Seller\CompanyData@legalR')->name('legalRV2');
	Route::get('/contact-person', 'Seller\CompanyData@contactPerson')->name('contactPersonV2');
	Route::get('/technical-information', 'Seller\CompanyData@technicalInformation')->name('technicalInformationV2');
	Route::get('/users', 'Seller\Users@index')->name('usersV2');
	Route::get('/address-office', 'Seller\Adresses@adressOffice')->name('adressOfficeV2');
	Route::get('/address-cont', 'Seller\Adresses@adressCont')->name('adressContV2');
	Route::get('/address-purchase', 'Seller\Adresses@addressPurchase')->name('addressPurchaseV2');
	Route::get('/orders', 'Seller\Orders@index')->name('ordersV2');
	Route::get('/orders-new', 'Seller\Orders@ordersNew')->name('ordersNewV2');
	Route::get('/orders-summary', 'Seller\Orders@ordersSummary')->name('ordersSummaryV2');
	Route::get('/orders-sucess/{id}', 'Seller\Orders@ordersSucess')->name('ordersSucessV2');
	Route::get('/orders-pdf/{id}/{id_empresa}', 'Seller\Orders@ordersPdf')->name('ordersPdfV2');
	Route::get('/orders-address-pdf/{id}/{id_morada}/{id_empresa}', 'Seller\Orders@ordersAdressPdf')->name('ordersAdressPdfV2');
	Route::get('/orders-details/{id}', 'Seller\Orders@ordersDetails')->name('ordersDetailsV2');
	Route::get('/orders-details-all/{id}', 'Seller\Orders@ordersDetailsAll')->name('ordersDetailsAllV2');
	Route::get('/points-history', 'Seller\Points@index')->name('pointsHistoryV2');
	Route::get('/premium-history', 'Seller\Points@premiumHistory')->name('premiumHistoryV2');
	Route::get('/change-points', 'Seller\Points@changePoints')->name('changePointsV2');
	Route::get('/premium-info/{id}', 'Seller\Points@premiumInfo')->name('premiumInfoV2');
	Route::get('/support', 'Seller\Support@index')->name('supportV2');
	Route::get('/ticket-new', 'Seller\Support@newTicket')->name('newTicketV2');
	Route::get('/ticket-msg/{id}', 'Seller\Support@msgTicket')->name('msgTicketV2');
	Route::post('/notifications-seller', 'Seller\Notifications@index')->name('notificationsV2');
	Route::post('/mark-all', 'Seller\Notifications@markAllNoti')->name('markAllNotiPost');
	Route::post('/filter-unread', 'Seller\Notifications@filterUnRead')->name('filterUnReadPost');
	Route::post('/mark', 'Seller\Notifications@markNoti')->name('markNotiPost');



	Route::post('/resend-email-seller', 'Seller\PersonalData@resendEmailSeller')->name('resendEmailSellerPostV2');
	Route::post('/cancel-email-seller', 'Seller\PersonalData@cancelEmailSeller')->name('cancelEmailSellerPostV2');
	Route::post('/save-personalData', 'Seller\PersonalData@saveData')->name('savePersonalDataPost');
	Route::post('/delete-photo', 'Seller\PersonalData@sellerPhotoDelete')->name('deleteCompanyAvatarPost');
	Route::post('/save-legalData', 'Seller\CompanyData@saveLegalData')->name('saveLegalDataPost');
	Route::post('/save-contactData', 'Seller\CompanyData@saveContactData')->name('saveContactDataPost');
	Route::post('/save-companyData', 'Seller\CompanyData@saveCompanyData')->name('saveCompanyDataPost');
	Route::post('/delete-companyAvatar', 'Seller\CompanyData@deleteCompanyAvatar')->name('deleteCompanyAvatarPost');
	Route::get('/validate-email-company/{token}', 'Seller\CompanyData@valiteEmail')->name('valiteEmailPost');


	Route::post('/add-user', 'Seller\Users@addUser')->name('addUserPost');
	Route::post('/delete-user', 'Seller\Users@deleteSeller')->name('deleteSellerPost');
	Route::post('/delete-photo-add', 'Seller\Users@photoDelete')->name('photoDeletePost');
	Route::post('/add-address', 'Seller\Adresses@addAdressOffice')->name('addAdressPost');
	Route::post('/add-resp', 'Seller\Adresses@addRespOffice')->name('addRespPost');
	Route::post('/change-status-address', 'Seller\Adresses@changeStatus')->name('changeStatusAdress');
	Route::post('/delete-address', 'Seller\Adresses@deleteAdress')->name('deleteAdressPost');
	Route::post('/order-next', 'Seller\Orders@nextOrder')->name('nextOrderPost');
	Route::post('/add-line', 'Seller\Orders@addLineProduct')->name('addLineProductPost');
	Route::post('/delete-line', 'Seller\Orders@deleteLine')->name('deleteLineCartPost');
	Route::post('/update-line', 'Seller\Orders@updateLine')->name('updateLinetPost');
	Route::post('/clean-datos', 'Seller\Orders@cleanDatos')->name('cleanDatosPost');
	Route::post('/cancel-order', 'Seller\Orders@cancelOrder')->name('cancelOrderPost');



	Route::post('/change-config', 'Seller\Dashboard@changeConfig')->name('changeConfigPost');
	Route::post('/order-comprovativo', 'Seller\Orders@addComprovativo')->name('addComprovativoPost');
	Route::post('/delete-comprovativo', 'Seller\Orders@deleteComprovativo')->name('deleteComprovativoPost');
	Route::post('/add-premium', 'Seller\Points@addPremiumCompany')->name('addPremiumCompanyPost');
	Route::post('/ask-send-premium', 'Seller\Points@askSendPremium')->name('askSendPremiumPost');

	Route::post('/new-ticket-save', 'Seller\Support@newTicketPost')->name('newTicketPostV2');
});


//Version
Route::get('/version/{token}', 'Backoffice\Version@index')->name('versionsPageB');
Route::post('/version-form', 'Backoffice\Version@versionForm')->name('versionsFormB');

/* BACKOFFICE */
Route::group(['prefix' => 'admin'], function () {
	Route::get('/', 'Backoffice\Login@index')->name('loginPageB');
	Route::post('/', 'Backoffice\Login@loginForm')->name('loginFormB');
	Route::get('/logout', 'Backoffice\Login@logout')->name('logoutPageB');
	Route::get('/lang/{lang}', 'Backoffice\Language@getLang')->name('setLanguageB');
	//restore
	Route::post('/restore', 'Backoffice\Login@restoreForm')->name('restoreFormB');
	Route::get('/restore-password/{token}', 'Backoffice\Login@restorePasswordPage')->name('emailRestorePageB');
	Route::post('/restore-password-form', 'Backoffice\Login@restorePasswordForm')->name('restorePasswordFormB');
	Route::get('/new-admin/{token}', 'Backoffice\Login@restorePasswordPage')->name('emailNewAdminPageB');

	/* BACKOFFICE - ÁREA RESERVADA */
	Route::group(['middleware' => ['Backoffice']], function () {
		Route::get('/dashboard', 'Backoffice\Dashboard@index')->name('dashboardPageB');
		//Minha conta
		Route::get('/admin-account', 'Backoffice\AdminAccount@index')->name('adminAccountPageB');
		Route::post('/admin-account-avatar', 'Backoffice\AdminAccount@accountAvatarForm')->name('adminAccAvaFormB');
		Route::post('/admin-account-avatar-delete', 'Backoffice\AdminAccount@accountAvatarApagar')->name('adminAccAvaApagarB');
		Route::post('/admin-account-dados', 'Backoffice\AdminAccount@accountDataForm')->name('adminAccDatFormB');
		//Administradores
		Route::get('/admin-all', 'Backoffice\Admin@index')->name('adminAllPageB');
		Route::post('/admin-all-delete', 'Backoffice\Admin@adminApagar')->name('adminAllApagarB');
		Route::get('/admin-new', 'Backoffice\Admin@adminNew')->name('adminNewPageB');
		Route::get('/admin-edit/{id}', 'Backoffice\Admin@adminEdit')->name('adminEditPageB');
		Route::post('/admin-new-edit', 'Backoffice\Admin@adminForm')->name('adminFormB');


		//Notificações
		Route::get('/notifications', 'Backoffice\Notifications@indexPage')->name('notificationsPageB');
		Route::get('/notifications-new', 'Backoffice\Notifications@newPage')->name('notificationsNewPageB');
		Route::get('/notifications-edit/{id}', 'Backoffice\Notifications@editPage')->name('notificationsEditPageB');
		Route::post('/notifications-new-edit', 'Backoffice\Notifications@form')->name('notificationsFormB');


		//Website - Slide
		Route::get('/slide-all', 'Backoffice\WebSlide@index')->name('webSlideAllPageB');
		Route::post('/slide-all-delete', 'Backoffice\WebSlide@apagar')->name('webSlideAllApagarB');
		Route::get('/slide-new', 'Backoffice\WebSlide@newPage')->name('webSlideNewPageB');
		Route::get('/slide-edit/{id}', 'Backoffice\WebSlide@editPage')->name('webSlideEditPageB');
		Route::get('/colors-all', 'Backoffice\WebSlide@colors')->name('colorsPageB');
		Route::get('/colors-new', 'Backoffice\WebSlide@colorsNew')->name('colorsNewPageB');
		Route::get('/colors-edit/{id}', 'Backoffice\WebSlide@colorsEdit')->name('colorsEditPageB');

		Route::post('/slide-new-edit', 'Backoffice\WebSlide@form')->name('webSlideFormB');
		Route::post('/colors-new-edit', 'Backoffice\WebSlide@formCor')->name('corFormB');

		//Passatempos
		Route::get('/giveaways-all', 'Backoffice\Giveaways@index')->name('giveawaysAllPageB');
		Route::post('/giveaways-all-delete', 'Backoffice\Giveaways@apagar')->name('giveawaysAllApagarB');
		Route::get('/giveaways-new', 'Backoffice\Giveaways@newPage')->name('giveawaysNewPageB');
		Route::get('/giveaways-edit/{id}', 'Backoffice\Giveaways@editPage')->name('giveawaysEditPageB');
		Route::post('/giveaways-new-edit', 'Backoffice\Giveaways@form')->name('giveawaysFormB');
		//Queijinho - Perguntas
		Route::get('/cheese-questions-all', 'Backoffice\Cheese@indexQuestions')->name('cheeseQuestionsAllPageB');
		Route::post('/cheese-questions-all-delete', 'Backoffice\Cheese@apagarQuestion')->name('cheeseQuestionsAllApagarB');
		Route::get('/cheese-questions-new', 'Backoffice\Cheese@newPageQuestion')->name('cheeseQuestionsNewPageB');
		Route::get('/cheese-questions-edit/{id}', 'Backoffice\Cheese@editPageQuestion')->name('cheeseQuestionsEditPageB');
		Route::post('/cheese-questions-new-edit', 'Backoffice\Cheese@formQuestion')->name('cheeseQuestionsFormB');
		//Queijinho - Premios
		Route::get('/cheese-awards-all', 'Backoffice\Cheese@indexAwards')->name('cheeseAwardsAllPageB');
		Route::post('/cheese-awards-all-delete', 'Backoffice\Cheese@apagarAward')->name('cheeseAwardsAllApagarB');
		Route::get('/cheese-awards-new', 'Backoffice\Cheese@newPageAward')->name('cheeseAwardsNewPageB');
		Route::get('/cheese-awards-edit/{id}', 'Backoffice\Cheese@editPageAward')->name('cheeseAwardsEditPageB');
		Route::post('/cheese-awards-new-edit', 'Backoffice\Cheese@formAward')->name('cheeseAwardsFormB');
		//Queijinho - FAQs
		Route::get('/cheese-faqs-all', 'Backoffice\Cheese@indexFAQs')->name('cheeseFAQsAllPageB');
		Route::post('/cheese-faqs-all-delete', 'Backoffice\Cheese@apagarFAQ')->name('cheeseFAQsAllApagarB');
		Route::get('/cheese-faqs-new', 'Backoffice\Cheese@newPageFAQ')->name('cheeseFAQsNewPageB');
		Route::get('/cheese-faqs-edit/{id}', 'Backoffice\Cheese@editPageFAQ')->name('cheeseFAQsEditPageB');
		Route::post('/cheese-faqs-new-edit', 'Backoffice\Cheese@formFAQ')->name('cheeseFAQsFormB');
		//Contactos
		Route::get('/contacts-all', 'Backoffice\Contacts@index')->name('contactsAllPageB');


		//Utilizadores
		Route::get('/users', 'Backoffice\Users@index')->name('usersPageB');
		Route::post('/users-delete', 'Backoffice\Users@delete')->name('usersDeleteB');
		Route::post('/users-resend-email', 'Backoffice\Users@resendEmail')->name('usersResendEmailB');
		Route::get('/users-login/{id}', 'Backoffice\Users@login')->name('usersLoginB');
		Route::get('/users-new', 'Backoffice\Users@new')->name('usersNewPageB');
		Route::get('/users-edit/{id}', 'Backoffice\Users@edit')->name('usersEditPageB');

		Route::post('/users-upload-photo', 'Backoffice\Users@uploadPhoto')->name('usersUploadPhotoFormB');
		Route::post('/users-delete-photo', 'Backoffice\Users@deletePhoto')->name('usersDeletePhotoFormB');
		Route::post('/users-edit-info-account', 'Backoffice\Users@usersAccountForm')->name('usersInfoAccountFormB');
		Route::post('/users-edit-info-personal', 'Backoffice\Users@usersPersonalForm')->name('usersInfoPersonalFormB');
		Route::post('/users-edit-addresses', 'Backoffice\Users@usersAddressesForm')->name('usersAddressesFormB');
		Route::post('/users-delete-points', 'Backoffice\Users@deletePoints')->name('usersDeletePointsFormB');
		Route::post('/users-new-edit-points', 'Backoffice\Users@pointsForm')->name('usersPointsFormB');




		//Comerciantes
		Route::get('/sellers', 'Backoffice\Sellers@sellers')->name('sellersPageB');
		Route::post('/sellers-delete', 'Backoffice\Sellers@sellersDelete')->name('sellersDeleteB');
		Route::post('/sellers-resend-email', 'Backoffice\Sellers@resendEmail')->name('sellersResendEmailB');
		Route::get('/sellers-login/{id}', 'Backoffice\Sellers@login')->name('sellersLoginB');
		Route::get('/sellers-new', 'Backoffice\Sellers@sellersNew')->name('sellersNewPageB');
		Route::get('/sellers-edit/{id}', 'Backoffice\Sellers@sellersEdit')->name('sellersEditPageB');
		Route::post('/sellers-upload-photo', 'Backoffice\Sellers@uploadPhoto')->name('sellersUploadPhotoFormB');
		Route::post('/sellers-delete-photo', 'Backoffice\Sellers@deletePhoto')->name('sellersDeletePhotoFormB');
		Route::post('/sellers-edit-info-account', 'Backoffice\Sellers@sellersAccountForm')->name('sellersInfoAccountFormB');
		Route::post('/sellers-edit-info-personal', 'Backoffice\Sellers@sellersPersonalForm')->name('sellersInfoPersonalFormB');
		Route::post('/sellers-edit-company', 'Backoffice\Sellers@sellersCompanyForm')->name('sellersCompanyFormB');
		//Empresas
		Route::get('/companies', 'Backoffice\Companies@companies')->name('companiesPageB');
		Route::post('/companies-delete', 'Backoffice\Companies@companiesDelete')->name('companiesDeleteB');
		Route::get('/companies-new', 'Backoffice\Companies@companiesNew')->name('companiesNewPageB');
		Route::get('/companies-edit/{id}', 'Backoffice\Companies@companiesEdit')->name('companiesEditPageB');
		Route::post('/companies-upload-photo', 'Backoffice\Companies@uploadLogo')->name('companiesUploadLogoFormB');
		Route::post('/companies-delete-photo', 'Backoffice\Companies@deleteLogo')->name('companiesDeleteLogoFormB');
		Route::post('/companies-edit-info-account', 'Backoffice\Companies@companiesAccountForm')->name('companiesInfoAccountFormB');
		Route::post('/companies-edit-products', 'Backoffice\Companies@companiesProductsForm')->name('companiesProductsFormB');
		Route::post('/companies-edit-company', 'Backoffice\Companies@companiesCompanyForm')->name('companiesCompanyFormB');

		//Encomendas
		Route::get('/orders', 'Backoffice\Orders@index')->name('ordersAllPageB');
		Route::get('/orders-warehouse/{id}', 'Backoffice\Orders@ordersWarehouse')->name('ordersWarehouseAllPageB');
		Route::get('/orders-edit/{id}', 'Backoffice\Orders@edit')->name('ordersEditPageB');
		Route::get('/orders-edit-total/{id}', 'Backoffice\Orders@editOrderTotal')->name('ordersEditTotalPageB');
		Route::get('/orders-new/{id}', 'Backoffice\Orders@newPageOrders')->name('ordersNewPageB');
		Route::get('/orders-invoice/{id}', 'Backoffice\Orders@ordersInvoice')->name('invoiceOrderPageB');

		//Communication
		Route::get('/communication', 'Backoffice\Communication@index')->name('mainPageComun');
		// add page 
		Route::get('/communication/add-page', 'Backoffice\Communication@addItemPage')->name('comunAdd');
		Route::post('/communication/add', 'Backoffice\Communication@addItemDB')->name('comunAdd_DB');
		// edit page 
		Route::get('/communication/edit-page/{id}', 'Backoffice\Communication@editarItem')->name('comunEdit');
		Route::post('/communication/edit', 'Backoffice\Communication@updateItem')->name('comunEdit_DB');
		// delete item 
		Route::post('/communication/delete', 'Backoffice\Communication@apagarItem')->name('comuniDelete');


		Route::post('/orders-addLine', 'Backoffice\Orders@addLineProduct')->name('ordersAddLineProductPageB');
		Route::post('/orders-form', 'Backoffice\Orders@formEdit')->name('ordersFormB');
		Route::post('/orders-total-form', 'Backoffice\Orders@formTotalEdit')->name('ordersTotalFormB');
		Route::post('/orders-delete', 'Backoffice\Orders@deleteOrder')->name('orderDeleteFormB');
		Route::post('/orders-invoice', 'Backoffice\Orders@ordersInvoiceForm')->name('orderInvoiceFormB');

		//Função do seller
		Route::get('/orders-pdf/{id}/{id_empresa}', 'Seller\Orders@ordersPdf')->name('ordersPdfB');
		Route::get('/orders-address-pdf/{id}/{id_morada}/{id_empresa}', 'Seller\Orders@ordersAdressPdf')->name('ordersAdressPdfB');









		//Prémios
		Route::get('/awards-all', 'Backoffice\Awards@index')->name('awardsAllPageB');
		Route::post('/awards-all-delete', 'Backoffice\Awards@apagar')->name('awardsAllApagarB');
		Route::get('/awards-new', 'Backoffice\Awards@newPage')->name('awardsNewPageB');
		Route::get('/awards-edit/{id}', 'Backoffice\Awards@editPage')->name('awardsEditPageB');
		Route::get('/awards-all-variants', 'Backoffice\Awards@newPageVariants')->name('awardsVariantsAllPageB');
		Route::get('/awards-variants-new', 'Backoffice\Awards@newVariantsPage')->name('newVariantsPageB');
		Route::get('/awards-variants-edit/{id}', 'Backoffice\Awards@editVariantsPage')->name('editVariantsPageB');
		Route::get('/awards-users', 'Backoffice\Awards@allUserAwards')->name('allUserAwardsPageB');
		Route::get('/awards-users-edit/{id}', 'Backoffice\Awards@editUserAwards')->name('editUserAwardsPageB');
		Route::get('/awards-seller', 'Backoffice\Awards@allSellerAwards')->name('allSellerAwardsPageB');
		Route::get('/awards-seller-edit/{id}', 'Backoffice\Awards@editSellerAwards')->name('editSellerAwardsPageB');

		Route::post('/awards-new-edit', 'Backoffice\Awards@form')->name('awardsFormB');
		Route::post('/awards-add-variant', 'Backoffice\Awards@awardsAdd')->name('awardsAddVariantB');
		Route::post('/awards-delete-variant', 'Backoffice\Awards@awardsDelete')->name('awardsDeleteVariantB');
		Route::post('/awards-variants-form', 'Backoffice\Awards@awardsVariantForm')->name('awardsVariantFormB');
		Route::post('/awards-variants-delete', 'Backoffice\Awards@awardsVariantDelete')->name('awardsVariantDeleteB');
		Route::post('/awards-users-form', 'Backoffice\Awards@editUserAwardsForm')->name('editUserAwardsFormB');
		Route::post('/awards-users-delete', 'Backoffice\Awards@deleteUserAwardsForm')->name('deleteUserAwardsFormB');

		Route::post('/awards-seller-form', 'Backoffice\Awards@editSellerAwardsForm')->name('editSellerAwardsFormB');
		Route::post('/awards-sellers-delete', 'Backoffice\Awards@deleteSellerAwardsForm')->name('deleteSellerAwardsFormB');


		//Rótulos
		Route::get('/labels', 'Backoffice\Labels@index')->name('labelsPageB');
		Route::post('/labels-list', 'Backoffice\Labels@indexList')->name('labelsListB');
		Route::get('/labels-new', 'Backoffice\Labels@newLabel')->name('labelsNewPageB');
		Route::get('/labels-edit/{id}', 'Backoffice\Labels@editLabel')->name('labelsEditPageB');
		Route::post('/labels-new-edit', 'Backoffice\Labels@formLabel')->name('labelsFormB');
		Route::get('/labels-generate', 'Backoffice\Labels@generatePage')->name('labelsGeneratePageB');
		Route::post('/labels-generate', 'Backoffice\Labels@generateForm')->name('labelsGenerateFormB');
		Route::get('/labels-identify', 'Backoffice\Labels@identifyPage')->name('labelsIdentifyPageB');
		Route::post('/labels-identify', 'Backoffice\Labels@identifyForm')->name('labelsIdentifyFormB');
		Route::get('/labels-export', 'Backoffice\Labels@exportPage')->name('labelsExportPageB');
		Route::post('/labels-export', 'Backoffice\Labels@exportForm')->name('labelsExportFormB');
		//Produtos
		Route::get('/products', 'Backoffice\Products@index')->name('productsAllPageB');
		Route::get('/products-new', 'Backoffice\Products@newPageProd')->name('productNewPageB');
		Route::get('/products-edit/{id}', 'Backoffice\Products@editPageProd')->name('productEditPageB');
		Route::post('/products-new-edit', 'Backoffice\Products@formProd')->name('formProdFormB');
		//Informacoes_Tecnicas
		Route::get('/technical-informations', 'Backoffice\TechnicalInformation@index')->name('techInfoPageB');
		Route::post('/technical-informations-delete', 'Backoffice\TechnicalInformation@delete')->name('techInfoDeleteFormB');
		Route::get('/technical-informations-new', 'Backoffice\TechnicalInformation@new')->name('techInfoNewPageB');
		Route::get('/technical-informations-edit/{id}', 'Backoffice\TechnicalInformation@edit')->name('techInfoEditPageB');
		Route::post('/technical-informations-form', 'Backoffice\TechnicalInformation@form')->name('techInfoFormB');
		//Configurações
		Route::get('/settings', 'Backoffice\Dashboard@settings')->name('settingsPageB');
		Route::get('/settings-edit/{id}', 'Backoffice\Dashboard@settingsEdit')->name('settingsEditPageB');
		Route::post('/settings-edit-form', 'Backoffice\Dashboard@settingsForm')->name('settingsFormB');




		//Gestão Documental
		Route::get('/management-document', 'Backoffice\ManagementDoc@index')->name('managementDocPageB');
		Route::get('/management-create-document', 'Backoffice\ManagementDoc@creatDocument')->name('creatDocumentPageB');
		Route::get('/management-edit-document/{id}', 'Backoffice\ManagementDoc@editDocument')->name('editDocumentPageB');
		Route::post('/management-document-delete', 'Backoffice\ManagementDoc@deleteLineDoc')->name('deleteLineDocFormB');
		Route::post('/management-create-doc', 'Backoffice\ManagementDoc@creatDocForm')->name('creatDocFormB');
		Route::post('/management-doc-aux-delete', 'Backoffice\ManagementDoc@deleteDocAux')->name('deleteDocAuxFormB');
		//Versões
		Route::get('/management-versions/{id}', 'Backoffice\ManagementDoc@versionsAll')->name('versionsAllPageB');
		Route::post('/management-versions-aprovation', 'Backoffice\ManagementDoc@versionsAprovation')->name('versionsAprovationFormB');
		Route::post('/management-versions-reprobation', 'Backoffice\ManagementDoc@versionsReprobation')->name('versionsReprobationFormB');
		Route::post('/management-versions-status', 'Backoffice\ManagementDoc@versionStatus')->name('versionStatusFormB');
		Route::post('/management-versions-delete', 'Backoffice\ManagementDoc@versionDelete')->name('versionDeleteFormB');

		//Certificações - Cookie
		Route::get('/gest-certification/{id}', 'Backoffice\GestViewer@index')->name('certificationsIdPageB');
		Route::get('/gest-certification-process/{id}', 'Backoffice\GestViewer@getProcess')->name('certificationsProcessPageB');


		//Certificações
		Route::get('/gest-certifications', 'Backoffice\Gest@indexCertifications')->name('certificationsPageB');
		Route::get('/gest-certifications-new', 'Backoffice\Gest@newCertification')->name('certificationsNewPageB');
		Route::get('/gest-certifications-edit/{id}', 'Backoffice\Gest@editCertification')->name('certificationsEditPageB');
		Route::post('/gest-certifications-new-edit', 'Backoffice\Gest@formCertification')->name('certificationsFormB');
		//Processos
		Route::get('/gest-processes/{id_cert}', 'Backoffice\Gest@indexProcesses')->name('processesPageB');
		Route::get('/gest-processes-new/{id_cert}', 'Backoffice\Gest@newProcess')->name('processesNewPageB');
		Route::get('/gest-processes-edit/{id_cert}/{id}', 'Backoffice\Gest@editProcess')->name('processesEditPageB');
		Route::post('/gest-processes-new-edit', 'Backoffice\Gest@formProcess')->name('processesFormB');
		//Actividades
		Route::get('/gest-activities/{id_proc}', 'Backoffice\Gest@indexActivities')->name('activitiesPageB');
		Route::get('/gest-activities-new/{id_proc}', 'Backoffice\Gest@newActivity')->name('activitiesNewPageB');
		Route::get('/gest-activities-edit/{id_proc}/{id}', 'Backoffice\Gest@editActivity')->name('activitiesEditPageB');
		Route::post('/gest-activities-new-edit', 'Backoffice\Gest@formActivity')->name('activitiesFormB');
		//Tarefas
		Route::get('/gest-tasks/{id_acti}', 'Backoffice\Gest@indexTasks')->name('tasksPageB');
		Route::get('/gest-tasks-new/{id_acti}', 'Backoffice\Gest@newTask')->name('tasksNewPageB');
		Route::get('/gest-tasks-edit/{id_acti}/{id}', 'Backoffice\Gest@editTask')->name('tasksEditPageB');
		Route::post('/gest-tasks-new-edit', 'Backoffice\Gest@formTask')->name('tasksFormB');

		//Siglas
		Route::get('/gest-acronyms', 'Backoffice\Gest@indexAcronyms')->name('acronymsPageB');
		Route::get('/gest-acronyms-new', 'Backoffice\Gest@newAcronym')->name('acronymsNewPageB');
		Route::get('/gest-acronyms-edit/{id}', 'Backoffice\Gest@editAcronym')->name('acronymsEditPageB');
		Route::post('/gest-acronyms-new-edit', 'Backoffice\Gest@formAcronym')->name('acronymsFormB');


		//Culinaria
		Route::get('/cooking-codes', 'Backoffice\Culinaria@index')->name('cookingPageB');
		Route::get('/cooking-codes-new', 'Backoffice\Culinaria@newCode')->name('newCodecookingPageB');
		Route::get('/cooking-codes-edit/{id}', 'Backoffice\Culinaria@editCode')->name('editCodecookingPageB');
		Route::post('/cooking-codes-new', 'Backoffice\Culinaria@formCode')->name('codeFormB');

		//Encomendas-Culinaria
		Route::get('/cooking-orders', 'Backoffice\Culinaria@ordersAll')->name('cookingOrdersPageB');
		Route::get('/cooking-orders-new', 'Backoffice\Culinaria@newOrder')->name('newOrdercookingPageB');
		Route::get('/cooking-orders-edit/{id}', 'Backoffice\Culinaria@editOrder')->name('editOrdersCookingPageB');
		Route::post('/cooking-orders-edit', 'Backoffice\Culinaria@formOrder')->name('orderFormB');

		//_TableManager
		Route::post('/TM-onoff', 'Backoffice\_TableManager@updateOnOff')->name('updateOnOffTMB');
		Route::post('/TM-delLine', 'Backoffice\_TableManager@deleteLine')->name('deleteLineTMB');
		Route::post('/TM-repDel', 'Backoffice\_TableManager@replaceDelete')->name('replaceDeleteTMB');
		Route::post('/TM-sort', 'Backoffice\_TableManager@sortTable')->name('sortTableTMB');
		Route::post('/TM-order', 'Backoffice\_TableManager@orderTable')->name('orderTableTMB');
	});
});
