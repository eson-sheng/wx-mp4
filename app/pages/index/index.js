//index.js
//获取应用实例
const app = getApp()
var uploadTask;

Page({
  data: {
    imgUrls: [],
    indicatorDots: true,
    indicatorColor: 'rgba(0, 0, 0, .3)',
    indicatorActiveColor : '#fff',
    vertical: false,
    autoplay: false,
    circular: true,
    interval: 2000,
    duration: 500,
    previousMargin: 0,
    nextMargin: 0,
    motto: '立即登录，上传视频',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    progress: 0,
  },
  onLoad: function(options){
    this.getAutoPlayImgUrls()
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getAutoPlayImgUrls: function(){
    var self = this;
    wx.request({
      url: app.config.apiUrl + '/lunbotu',
      method: 'POST',
      data: {
        tu: 'getAutoPlayImgUrls'
      },
      success(res) {
        console.log(res.data.data)        
        self.setData({
          imgUrls: res.data.data,
        })
      }
    })
  },
  getUserInfo: function (e) {
    console.log(e)
    if (e.detail.rawData) {
      wx.login({
        success(res) {
          if (res.code) {
            // 发起网络请求
            console.log(res);
          } else {
            console.log('登录失败！' + res.errMsg)
          }
        }
      })
      app.globalData.userInfo = e.detail.userInfo
      this.setData({
        userInfo: e.detail.userInfo,
        hasUserInfo: true
      })
    }
  },
  toHref: function(e){
    console.log(e.currentTarget.dataset.href);
    wx.navigateTo({
      url: e.currentTarget.dataset.href
    })
  },
  uploadVideo: function (e) {
    var self = this;
    wx.chooseVideo({
      success(res) {

        const tempFilePaths = [res.tempFilePath, res.thumbTempFilePath]
        console.log(res);
        
        uploadTask = wx.uploadFile({
          url: app.config.apiUrl + '/video/upload',
          filePath: tempFilePaths[0],
          name: 'file',
          formData: {
            name: new Date().getTime(),
            author: app.globalData.userInfo.nickName
          },
          success(res) {
            var json = JSON.parse(res.data);
            console.log(json);
            if (json.status) {
              wx.showToast({
                title: '成功',
                icon: 'success',
                duration: 2000
              })
            } else {
              wx.showToast({
                title: '失败',
                icon: 'success',
                duration: 2000
              })
            }
            self.setData({
              progress: 0,
            })
          }
        })

        uploadTask.onProgressUpdate((res) => {
          self.setData({
            progress: res.progress,
          })
        })
      }
    })
  },
  abort: function(e){
    if (uploadTask) {
      var self = this;
      self.setData({
        progress: 0,
      })
      uploadTask.abort();
    }
  }
})
