$(function () {
    var obj = {
        _self: null,
        init: function () {
            _self = this;
            $('[data-bt=f5]').click(function () {
                _self.branches();
            });
            $('[name=projectId]').change(function () {
                _self.branches();
            });
        },
        branches: function () {
            var projectId = $('[name=projectId]').val();
            $.get('/project/get-branches-list', {'id':projectId}, function(d) {
                if (d.code == 0) {
                    _self._analyze(d.data);
                } else {
                    alert(d.msg);
                }
            }, 'json');
        },
        _analyze: function (branches) {
            $branches = $('[name=branches]');
            $branches.html('');
            $.each(branches, function (i, val) {
                $branches.append('<option value="' + val + '">' + val + '</option>');
            });
        }
    };
    obj.init();
    obj.branches();
});