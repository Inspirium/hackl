<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::group(['middleware' => 'club'], function () {
        //old login, replaced with passport routes
        Route::post('login', 'AuthController@login');
        Route::post('login/refresh', 'AuthController@refresh');
        Route::post('logout', 'AuthController@logout');
        // Route::post('register', 'AuthController@register');
        Route::post('password/email', 'AuthController@passwordEmail');
        Route::post('password/reset', 'AuthController@passwordReset');
        Route::post('password/token', 'AuthController@passwordToken');

        Route::get('club', 'ClubController@getClub'); //replaced club.this

        Route::get('club/courts/{day?}', 'ClubController@getCourts'); //replaced with court.index
        Route::get('club/schedule/{day?}', 'ClubController@getDoubleReservations'); // TODO:

        Route::get('club/court/{court}/{date?}', 'CourtController@getCourt'); // replaced with court.show

        Route::put('club/weather', 'ClubController@weatherUpdate'); // club.update

        Route::put('club/save_image', 'ClubController@saveImage'); // club.update

        Route::get('surfaces', 'AdminController@getSurfaces'); // surface.index

        // routes that need user to be signed in
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('club/reservation', 'ClubController@postReservation'); // reservation.store
            Route::delete('club/reservation/{reservation}', 'ClubController@deleteReservation'); // reservation.destroy
            Route::post('club/reservation/{reservation}/friend', 'ClubController@addPlayersToReservation'); // TODO:reservation.update
            Route::delete('club/reservation/{reservation}/friend/{player}', 'ClubController@removePlayersFromReservation'); // TODO: reservation.update
            Route::post('club/reservation/{reservation}/repeat', 'ClubController@repeatReservation'); // TODO: reservation.update

            Route::get('club/double_reservations', 'ClubController@getDoubleReservations');

            Route::get('me', 'UserController@getUser'); // player.me
            Route::get('me/results', 'UserController@getResults'); // TODO
            Route::put('player/{player}', 'PlayerController@putPlayer'); //player.update
            Route::put('player/{player}/remove_image', 'PlayerController@removeImage'); //TODO: player.update
            Route::put('player/{player}/save_image', 'PlayerController@saveImage'); //player.update
            Route::put('player/{player}/password', 'PlayerController@savePassword'); //player.update

            Route::post('players', 'PlayerController@getPlayers'); //player.index

            Route::get('player/{player}', 'PlayerController@getPlayer'); //player.show
            Route::get('player/{player}/me', 'PlayerController@getPlayerVS'); // TODO
            Route::post('player', 'PlayerController@postPlayer'); // player.update

            Route::get('player/{player}/reservations', 'PlayerController@getReservations'); // reservation.index

            Route::get('club/results/{type?}/{offset?}', 'ResultsController@getResults'); // TODO: result.index
            Route::get('club/result/{result}', 'ResultsController@getResult'); // TODO: result.show
            Route::post('club/result/{result}/verify', 'ResultsController@verifyResult'); // TODO: result.update
            Route::post('club/result', 'ResultsController@postResult'); // TODO: result.store
            Route::post('club/result/{result}/comment', 'ResultsController@postComment'); // TODO: result.comment
            Route::delete('club/result/{result}', 'ResultsController@deleteResult'); // TODO: result.destroy
            Route::delete('club/result/{result}/dispute', 'ResultsController@disputeResult'); // TODO: result.dispute

            Route::get('notifications', 'NotificationsController@getNotifications'); // notification.index
            Route::get('notifications/all', 'NotificationsController@getAllNotifications'); // notification.index
            Route::post('notifications/read', 'NotificationsController@markAllRead'); //TODO notification.put
            Route::delete('notification/{notification}', 'NotificationsController@deleteNotification'); //TODO notification.destroy
            Route::post('notification/{notification}', 'NotificationsController@markAsRead'); //TODO notification.put

            Route::get('threads', 'MessagesController@getThreads'); //TODO: thread.index
            Route::group(['prefix' => 'thread'], function () {
                Route::get('{thread}', 'MessagesController@getThread'); //TODO: thread.show
                Route::post('{thread}', 'MessagesController@getMoreThread'); //TODO:thead.messages.index
                Route::post('/', 'MessagesController@postThread'); //TODO: thead.store
                Route::put('{thread}', 'MessagesController@putThread'); //TODO: thread.update
                Route::delete('{thread}', 'MessagesController@deleteThread'); //TODO: thread.destroy

                Route::get('new/{id?}', 'MessagesController@newThread'); //TODO

                Route::post('{thread}/message', 'MessagesController@postMessage'); //TODO:
                Route::put('{thread}/message/{message}', 'MessagesController@putMessage'); //TODO:
                Route::delete('{thread}/message/{message}', 'MessagesController@deleteMessage'); //TODO
            });

            Route::any('classifieds', 'ClassifiedsController@getClassifieds'); // TODO: classified.index
            Route::get('classified/{classified}', 'ClassifiedsController@getClassified'); // TODO: classified.show
            Route::put('classified/{classified}', 'ClassifiedsController@putClassified'); // TODO: classified.update
            Route::delete('classified/{classified}', 'ClassifiedsController@deleteClassified'); // TODO: classified.destroy
            Route::post('classified', 'ClassifiedsController@postClassified'); // TODO: classified.store
            Route::post('classified/{classified}/comment', 'ClassifiedsController@postComment'); // TODO: classified.comment

            Route::group(['prefix' => 'admin'], function () {
                Route::put('club/{id}', 'AdminController@putClub');
                Route::get('court/{court}', 'AdminController@getCourt');
                Route::post('court', 'AdminController@postCourt');
                Route::post('court/{court}/hours', 'AdminController@postHours');
                Route::delete('court/{court}/hours/{hours}', 'AdminController@deleteHours');
                Route::put('court/{court}', 'AdminController@putCourt');
                Route::delete('court/{court}', 'AdminController@putCourt');

                Route::get('club/players/{type}', 'AdminController@getPlayers');
                Route::put('club/player/{player}', 'AdminController@putPlayer');
            });
        });

        Route::group(['middleware' => 'auth:api', 'prefix' => 'super'], function () {
            Route::post('players', 'Super\SuperController@getPlayers');
        });
    });

    Route::group(['prefix' => 'v2', 'namespace' => '\App\Http\Controllers\V2'], function () {
        // oauth login
        Route::get('login/{service}', 'OAuthController@redirect')->middleware('web');
        Route::get('login/{service}/callback', 'OAuthController@callback');
        Route::post('login/connect', 'OAuthController@connect');
        Route::post('login/reset', 'OAuthController@resetPassword');
        Route::post('login/new_reset', 'OAuthController@resetNewPassword');
    });

    Route::group(['prefix' => 'v2'], function () {
        Route::get('game/{game}/live', [\App\Http\Controllers\V2\LiveResultController::class, 'show']);

        Route::get('status', function () {
            return response()->json(['status' => 'ok', 'appVersion' => '1.38.0', 'ios' => '1380', 'android' => '1380']);
        });
    });

    Route::group(['prefix' => 'v2', 'namespace' => '\App\Http\Controllers\V2', 'middleware' => 'club'], function () {
        Route::group(['prefix' => 'import'], function () {
            Route::post('users', [\App\Http\Controllers\V2\ImportController::class, 'users']);
        });
        //club
        Route::get('club/this', 'ClubController@this');

        Route::post('user/popups', [\App\Http\Controllers\V2\HiddenNotificationController::class, 'store']);

        Route::get('league/{league}/random', 'League\IndexController@randomLeague');
        Route::get('league/{league}/strength', 'League\IndexController@strengthLeague');
        Route::get('league/{league}/inherit', 'League\IndexController@inheritLeague');
        Route::get('league/{league}/copy', 'League\IndexController@copyLeague');
        Route::get('league/{league}/clear', 'League\IndexController@clearLeague');
        Route::get('league/{league}/start_new', 'League\IndexController@startNew');
        Route::post('league/{league}/order', 'League\IndexController@groupOrder');

        Route::apiResource('classified.comment', 'CommentController')->parameter('classified', 'id');
        Route::apiResource('result.comment', 'CommentController')->parameter('result', 'id');

        Route::get('league/{id}/clear_players', 'ObjectPlayerController@clearPlayers');
        Route::get('league_group/{id}/clear_players', 'ObjectPlayerController@clearPlayers');
        Route::get('league_group/{id}/game', 'GameController@index');

        Route::get('tournament/{tournament}/import/{league}', 'Tournament\IndexController@pullPlayersFromLeague');
        Route::get('tournament/{tournament}/random', 'Tournament\IndexController@random');
        Route::get('tournament/{tournament}/seed', 'Tournament\IndexController@seed');
        Route::get('tournament/{tournament}/strength', 'Tournament\IndexController@strength');

        Route::apiResource('league.document', 'DocumentController')->parameter('league', 'id');
        Route::apiResource('league.player', 'ObjectPlayerController')->parameter('league', 'id');
        Route::apiResource('league_group.player', 'ObjectPlayerController')->parameter('league_group', 'id');
        Route::apiResource('tournament.player', 'ObjectPlayerController')->parameter('tournament', 'id');
        Route::apiResource('game.player', 'ObjectPlayerController')->parameter('game', 'id');
        Route::apiResource('team.player', 'ObjectPlayerController')->parameter('team', 'id');
        Route::apiResource('tournament.document', 'DocumentController')->parameter('tournament', 'id');
        Route::apiResource('tournament.game', 'GameController')->parameter('tournament', 'id');

        Route::apiResource('school_group.invoice', 'ObjectInvoicesController')->parameter('school_group', 'object_id');
        Route::apiResource('membership.invoice', 'ObjectInvoicesController')->parameter('membership', 'object_id');
        Route::get('result/{result}/verify', 'ResultController@verify');
        Route::get('result/{result}/dispute', 'ResultController@dispute');
        Route::put('club/court/order', 'CourtController@order');
        Route::put('club/member', 'ClubMemberController@update');
        Route::post('club/member/elo', 'ClubMemberController@eloPoints');

        Route::post('reservation/deleteMany', 'ReservationController@deleteMany');
        Route::get('court/free', [\App\Http\Controllers\V2\FreeReservationController::class, 'index']);

        // live
        Route::post('game/{game}/live', [\App\Http\Controllers\V2\LiveResultController::class, 'store']);
        Route::put('game/{game}/live', [\App\Http\Controllers\V2\LiveResultController::class, 'update']);
        Route::get('reservation/stats', [\App\Http\Controllers\V2\Reservation\StatisticController::class, 'index']);
        Route::post('invoice/approve', [\App\Http\Controllers\V2\InvoiceController::class, 'approve']);
        Route::get('invoice/{invoice}/pdf', [\App\Http\Controllers\V2\InvoiceController::class, 'pdf']);
        Route::post('invoice/send', [\App\Http\Controllers\V2\InvoiceController::class, 'send']);
        Route::delete('invoice/delete-many', [\App\Http\Controllers\V2\InvoiceController::class, 'deleteMany']);
        Route::get('competition/{competition}/teams', [\App\Http\Controllers\V2\CompetitionController::class, 'teams']);
        Route::get('club/social/{service}', [\App\Http\Controllers\V2\ClubSocialController::class, 'index']);
        Route::apiResources([
            'work-order' => 'Shop\WorkOrderController',
            'club' => 'ClubController',
            'club.membership' => 'MembershipsController',
            'club.other_expense' => 'OtherExpenseController',
            'club.payment' => 'PaymentController',
            'player' => 'UserController',
            'player.membership' => 'UserMembershipController',
            'notifications' => 'NotificationTypeController',
            'reservation_category' => 'ReservationCategoryController',
            'hours_category' => 'HourCategoryController',
            'reservation' => 'ReservationController',
            'reservation.payment' => 'ReservationPaymentController',
            'membership.payment' => 'MembershipPaymentController',
            'court' => 'CourtController',
            'court.hours' => 'WorkingHoursController',
            'result' => 'ResultController',
            'thread' => 'ThreadController',
            'thread.message' => 'MessageController',
            'message' => 'MessageController',
            'surface' => 'SurfaceController',
            'classified' => 'ClassifiedController',
            'news' => 'NewsController',
            'trainer' => 'TrainerController',
            'school_group' => 'SchoolGroup\IndexController',
            'school_group.player' => 'SchoolGroup\PlayerController',
            'school_group.trainer' => 'SchoolGroup\TrainerController',
            'attendance' => 'SchoolGroup\AttendanceController',
            'tournament' => 'Tournament\IndexController',
            'tournament_round' => 'TournamentRoundController',
            'league' => 'League\IndexController',
            'league_group' => 'League\GroupController',
            'game' => 'GameController',
            'document' => 'DocumentController',
            'team' => 'TeamController',
            'wallet' => 'WalletController',
            'wallet_transaction' => 'WalletTransactionController',
            'media' => 'MediaController',
            'player_profile' => 'PlayerProfileController',
            'tournament_score' => 'Tournament\ScoreController',
            'sport' => 'SportController',
            'equipment' => 'EquipmentController',
            'school_performance' => 'SchoolPerformanceController',
            'subscription' => 'SubscriptionController',
            'subscription.player' => 'UserSubscriptionController',
            'user_subscription' => 'UserSubscriptionController',
            'invoice' => 'InvoiceController',
            'city' => 'CityController',
            'country' => 'CountryController',
            'tax_class' => 'TaxClassController',
            'business-units' => 'BusinessUnitController',
            'company' => 'CompanyController',
            'court_weather_update' => 'CourtWeatherUpdateController',
            'sponsor' => 'SponsorController',
            'competition' => 'CompetitionController',
            'trainer-notes' => 'TrainerNoteController',
            'location' => 'LocationController',
            'payment' => 'PaymentController',
        ]);

        //Route::get('player', [\App\Http\Controllers\V2\TeamController::class, 'index']);

        Route::group(['prefix' => 'shop'], function () {
            Route::apiResources([
                'product' => 'Shop\ProductController',
                'order.work-order' => 'Shop\WorkOrderController',
                'order/buyers' => 'Shop\BuyerController',
                'order/assigners' => 'Shop\AssignerController',
                'order' => 'Shop\OrderController',
                'category' => 'Shop\CategoryController',
            ]);
        });

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('stripe/intent', [\App\Http\Controllers\V2\PaymentIntentController::class, 'store']);
            Route::group(['prefix' => 'thread/{thread}'], function () {
                Route::apiResource('message', 'MessageController');
            });

            Route::get('me', 'UserController@me');
            Route::post('me/set-locale', 'UserController@setLocale');
            Route::get('ranking/home', 'RankingsController@my_club_rankings');

            //notifications
            Route::get('notification', 'NotificationController@index');
            Route::put('notification/{id}', 'NotificationController@update');
            Route::delete('notification/{id}', 'NotificationController@delete');
            Route::get('notification/all', 'NotificationController@mark_all');
            Route::get('notification/number', 'NotificationController@number');
            Route::post('notification/subscribe', 'NotificationController@subscribe');
            Route::post('notification/check', 'NotificationController@checkToken');
            Route::post('notification', 'NotificationController@send');

        });
        Route::get('export/players', 'ExportController@players');
        Route::get('export/traffic/{type?}', 'InvoiceReportController@index');
        Route::get('export/payments/{type?}', 'PaymentReportController@index');
        Route::post('contact', [\App\Http\Controllers\V2\ContactController::class, 'submit']);
    });

    Route::group(['prefix' => 'v2'], function () {
        Route::any('webhook', [\App\Http\Controllers\V2\WebhookController::class, 'index']);
    });

    Route::group(['prefix' => 'emails'], function () {
        Route::get('reset_password', function () {
            return (new \App\Notifications\ResetPasswordNotification('token123', \App\Models\Club::find(1)))->toMail(\App\Models\User::find(2));
        });

        Route::get('subscription', function () {
            return (new \App\Mail\PaymentInfoSubscription(\App\Models\Subscription::find(12), \App\Models\UserSubscription::find(133), \App\Models\User::find(2)))->render();
        });
        Route::get('late_payment', function () {
            return (new \App\Mail\LatePayment());
        });
    });

    Route::group(['prefix' => 'pdf'], function () {
        Route::get('subscription', function () {
            $t = \App\Models\Subscription::find(12);
            return PDF::loadView('invoices.subscription', ['subscription' => $t])->stream('racun1.pdf');
        });
    });


