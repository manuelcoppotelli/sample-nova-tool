Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'sample-tool',
      path: '/sample-tool',
      component: require('./components/Tool').default,
    },
  ])
})
