import Admin from './components/admin/Admin.vue';
import TopLevel from './components/TopLevel'

export const routes = [
    { path: '/login', component: require('./components/auth/Login'), meta: { title: 'Prijava' } },
    { path: '/register', component: require('./components/auth/Register'), meta: { title: 'Registracija' } },
    { path: '/password/email', component: require('./components/auth/PasswordEmail'), meta: { title: 'Oporavak lozinke' } },
    { path: '/password/reset/:token', component: require('./components/auth/PasswordReset'), meta: {title: 'Oporavak lozinke' } },

    { path: '/', component: require('./components/Init'),
        children: [
            { path: '', component: require('./components/Index'), meta: { title: 'Naslovnica' } },

            { path: 'classifieds', component: require('./components/classified/Classifieds'), meta: { title: 'Oglasnik' } },
            { path: 'club/classifieds', component: require('./components/classified/Classifieds'), meta: { title: 'Oglasnik' } },
            { path: 'classified/new', component: require('./components/classified/ClassifiedEdit'), meta: { title: 'Oglasnik' } },
            { path: 'classified/:id', component: require('./components/classified/Classified'), meta: { title: 'Oglasnik' } },
            { path: 'classified/:id/edit', component: require('./components/classified/ClassifiedEdit'), meta: { title: 'Oglasnik' } },

            { path: 'club', component: TopLevel, children: [
                { path: 'about', component: require('./components/About'), meta: { title: 'O klubu' } },
                { path: 'news', component: TopLevel, children:[
                        { path: '', component: require('./components/news/News'), meta: {title: 'Novosti'} },
                        { path: ':category', component: require('./components/news/News'), meta: {title: 'Category'}, children: [
                                {path: ':slug', component: require('./components/news/Single'), meta: {title: 'Title'}}
                            ] }
                    ] },
                { path: 'schedule', component: require('./components/schedule/Schedule'), meta: { title: 'Pregled mečeva' } },

                { path: 'ranks', component: require('./components/Ranks'), meta: { title: 'Rang ljestvica' } },
                { path: 'results', component: TopLevel,
                    children: [
                        { path: '', component: require('./components/results/Results'), meta: { title: 'Rezultati' } },
                        { path: 'new', component: require('./components/results/ResultsNew'), meta: { title: 'Novi Rezultat', requiresAuth: true } },
                        { path: 'result/:id', component: require('./components/results/Result'), meta: { title: 'Rezultat', requiresAuth: true } },

                    ] },
                { path: 'courts', component: require('./components/Courts'), meta: { title: 'Tereni' } },
                { path: 'court/:id/:date?', component: require('./components/Court'), meta: { title: 'Teren' } },
                { path: 'players', component: require('./components/player/Players'), meta: { title: 'Igrači', requiresAuth: true } },

        ]},

            { path: 'player/:id', component: require('./components/player/Player'), meta: { title: 'Igrač', requiresAuth: true } },

            { path: 'players', component: require('./components/player/Players'), meta: { title: 'Svi Igrači', requiresAuth: true } },

            { path: 'thread', component: TopLevel, children: [
            { path: 'new', component: require('./components/messages/ThreadStart'), meta: { title: 'Nova poruka', requiresAuth: true } },
            { path: ':id', component: require('./components/messages/Thread'), meta: { title: 'Poruke', requiresAuth: true } },
        ]},

            { path: 'me', component: TopLevel, children: [
            { path: '', component: require('./components/player/Player'), meta: { title: 'Moj profil', requiresAuth: true } },
            { path: 'profile', component: require('./components/player/Profile'), meta: {title: 'Moji podaci', requiresAuth: true } },
            { path: 'reservations', component: require('./components/player/Reservations'), meta: { title: 'Rezervacije', requiresAuth: true } },
            { path: 'messages', component: require('./components/messages/Messages'), meta: { title: 'Poruke', requiresAuth: true } },
            { path: 'message/new/:id', component: require('./components/messages/ThreadNew'), meta: { title: 'Poruke', requiresAuth: true } },
            { path: 'message/:id', component: require('./components/messages/Thread'), meta: { title: 'Poruke', requiresAuth: true } },

            { path: 'challengers', component: require('./components/player/Challengers'), meta: { title: 'Poruke', requiresAuth: true } },
            { path: 'notifications', component: require('./components/player/Notifications'), meta: { title: 'Obavijesti', requiresAuth: true } },
        ] },

            { path: 'admin', component: TopLevel, children: [
            { path: '/', component: Admin, meta: { title: 'Administracija', requiresAuth: true }},
            { path: 'club', component: require('./components/admin/Club'), meta: { title: 'Klub', requiresAuth: true }},
            { path: 'courts', component: require('./components/admin/Courts'), meta: { title: 'Tereni', requiresAuth: true } },
            { path: 'court/:id?', component: require('./components/admin/Court'), meta: { title: 'Teren', requiresAuth: true } },
            { path: 'players', component: require('./components/admin/Players'), meta: { title: 'Članovi', requiresAuth: true } }
            ] },

            { path: 'super', component: TopLevel, children: [
            { path: '/', component: require('./components/super/Index'), meta: {title: 'Super Admin', requiresAuth: true} },
            { path: 'surfaces', component: require('./components/super/Surfaces'), meta: {title: 'Podloge', requiresAuth: true} },
            { path: 'clubs', component: require('./components/super/Clubs'), meta: {title: 'Podloge', requiresAuth: true} },
            { path: 'players', component: require('./components/super/Players'), meta: {title: 'Igrači', requiresAuth: true} },
            ] }
        ]
    },
    { path: '*', component: require('./components/p404'), meta: {title: 'Nije pronađeno'} }
];
