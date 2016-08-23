$(function () {
    var obj = {
        _self: null,
        _timer: null,
        _taskId: 0,
        init: function () {
            _self = this;
            _taskId = $('[data-bt=pub]').data('taskId');
            $('[data-bt=pub]').click(function () {
                _self.start_pub();
            });
        },
        start_pub: function () {
            // 发送开始请求
            $.get('/site/start-pub', {id:_taskId});
            // 执行状态时钟
            _self.start_timer();
            // 禁用按钮
            $('[data-bt=pub]').attr('disabled', 'disabled');
        },
        start_timer: function () {
            _self._timer = setInterval(function () {
                _self._get_status();
            }, 1000);
        },
        stop_timer: function () {
            clearInterval(_self._timer);
        },
        _get_status: function () {
            var progress = $('#progress');
            $.get('/site/get-pub-status', {id:_taskId}, function (d) {
                if (d.code == 100) {
                    _self.stop_timer();
                } else if (d.code == -1) {
                    progress.css('background-color', 'red');
                    _self.stop_timer();
                } else {
                    progress.css('width', d.code + '%');
                }
            }, 'json');
        }
    };
    obj.init();
});