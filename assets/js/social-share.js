document.addEventListener('DOMContentLoaded', function () {
    if (!window.Limaomao || !Limaomao.share_enabled) return;

    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.type;
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            let shareUrl = '';

            switch(type) {
                case 'weibo':
                    shareUrl = `https://service.weibo.com/share/share.php?url=${url}&title=${title}`;
                    break;
                case 'wechat':
                    alert('请点击浏览器右上角菜单分享到微信');
                    return;
                case 'qq':
                    shareUrl = `https://connect.qq.com/widget/shareqq/index.html?url=${url}&title=${title}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                default:
                    return;
            }
            if (shareUrl) window.open(shareUrl, '_blank', 'width=600,height=400');
        });
    });
});
