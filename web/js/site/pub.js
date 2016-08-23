$(function () {
    var obj = {
        _self: null,
        _timer: null,
        init: function () {
            _self = this;
            $('[data-bt=pub]').click(function () {
                _self.start_pub();
            });
        },
        start_pub: function () {
            $pub = $('[data-bt=pub]');
            $taskId = $pub.data('taskId');
            // 发送开始请求
            $.get('/site/start-pub', {id:$taskId});
            // 执行状态时钟
            _self.start_timer();
            $pub.attr('disabled', 'disabled');
        },
        start_timer: function () {
            _self._timer = setTimeout(function () {
                _self._get_status();
            }, 1000);
        },
        stop_timer: function () {
            clearTimeout(_self._timer);
        },
        _get_status: function () {
            var progress = $('#progress');
            progress.css('width', '10%');
        }
    };
    obj.init();
});