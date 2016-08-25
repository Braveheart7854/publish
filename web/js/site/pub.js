$(function () {
    var obj = {
        _self: null,
        _timer: null,
        _taskId: 0,
        init: function () {
            _self = this;
            _taskId = $('[data-bt=pub]').data('taskid');
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
            $('#progress').css('width', '10%');
            $('[data-bt=msg]').text('开始同步');
        },
        start_timer: function () {
            _self._timer = setInterval(function () {
                _self._get_status();
            }, 600);
        },
        stop_timer: function () {
            clearInterval(_self._timer);
        },
        _get_status: function () {
            var progress = $('#progress');
            var msg = $('[data-bt=msg]');
            $.get('/site/get-pub-status', {id:_taskId}, function (d) {
                msg.text(d.msg);
                if (d.code == -1) {
                    progress.css('background-color', 'red');
                    _self.stop_timer();
                } else {
                    progress.css('width', d.code + '%');
                    if (d.code == 100) {
                        _self.stop_timer();
                    }
                }
            }, 'json');
        }
    };
    obj.init();
});