
const routes = [
  {
    path: '/',
    component: () => import('pages/Index.vue'),
  },
  {
    path: '/:page',
    component: () => import('src/pages/Page.vue'),
  },
  {
    path: '/:language/home',
    component: () => import('src/pages/Fat.vue'),
  },
  {
    path: '/static-landing-page',
    component: () => import('src/pages/LandingPage.vue'),
  },
  {
    path: '/static-home',
    component: () => import('pages/Home.vue')
  },
  {
    path: '/static-document',
    component: () => import('pages/Document.vue')
  },
  {
    path: '/static-about-us',
    component: () => import('pages/AboutUs.vue')
  },
  {
    path: '/error',
    component: () => import('pages/Error404.vue')
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
