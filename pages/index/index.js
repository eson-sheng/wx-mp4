//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    background: [
      'https://images.unsplash.com/photo-1551334787-21e6bd3ab135?w=640',
      'https://images.unsplash.com/photo-1551214012-84f95e060dee?w=640',
      'https://images.unsplash.com/photo-1551446591-142875a901a1?w=640'
    ],
    indicatorDots: true,
    indicatorColor: 'rgba(0, 0, 0, .3)',
    indicatorActiveColor : '#fff',
    vertical: false,
    autoplay: false,
    circular: true,
    interval: 2000,
    duration: 500,
    previousMargin: 0,
    nextMargin: 0
  }
})
