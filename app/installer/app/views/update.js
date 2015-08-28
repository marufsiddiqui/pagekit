var Output = require('../components/output.vue');
var Version = require('../lib/version');

module.exports = {

    data: function () {
        return _.extend({
            view: 'index',
            status: null,
            update: false,
            output: '',
            progress: 0,
            errors: []
        }, window.$data);
    },

    created: function () {
        this.getVersions();
    },

    computed: {

        hasUpdate: function () {
            return this.update && Version.compare(this.update.version, this.version, '>');
        }

    },

    methods: {

        getVersions: function () {

            this.$http.get(this.api + '/update', function (data) {
                this.$set('update', data[this.channel == 'nightly' ? 'nightly' : 'latest']);
            }).error(function () {
                this.errors.push(this.$trans('Cannot connect to the server. Please try again later.'));
            });

        },

        install: function () {
            this.$set('view', 'installation');
            this.doDownload(this.update);
        },

        doDownload: function (update) {
            this.$set('progress', 33);
            this.$http.post('admin/system/update/download', {url: update.url, shasum: update.shasum})
                .success(this.doInstall)
                .error(this.error);
        },

        doInstall: function (data) {
            var vm = this;

            this.$set('progress', 66);
            this.$http.get('', {file: data.file, token: data.token}, null, {
                headers: {'X_UPDATE_MODE': true},
                beforeSend: function (request) {
                    request.onprogress = function () {
                        vm.setOutput(this.responseText);
                    };
                }
            }).success(this.doMigration)
                .error(this.error);
        },

        doMigration: function () {
            this.$set('progress', 100);
            if (this.status === 'success') {
                // TODO: Implement this.
            }
        },

        setOutput: function (output) {
            var lines = output.split("\n");
            var match = lines[lines.length - 1].match(/^status=(success|error)$/);

            if (match) {
                this.status = match[1];
                delete lines[lines.length - 1];
                this.output = lines.join("\n");
            } else {
                this.output = output;
            }

        },

        error: function (error) {
            this.errors.push(error || this.$trans('Whoops, something went wrong.'));
        }

    }

};

jQuery(function () {

    (new Vue(module.exports)).$mount('#update');

});
