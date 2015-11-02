
(function (reqs) {
    var $ = reqs.$;
    var debug = reqs.debug;
    var fileSaver = reqs.fileSaver;
    var document = reqs.document;
    var MG = reqs.MG;
    var queryParams = reqs.queryParams;
    var CSV = reqs.queryParams;
    var Clipboard = reqs.queryParams;
    var logger = reqs.logger;

    var index;
    var hiddenCells = $('tr td .json');
    var totalDocuments = hiddenCells.length;
    var timeSeries = [];
    var cell;
    var rawDocument;
    var status;
    var regExp;
    var escapedDocument;
    var dateAccessor = 'created_at';
    var textAccessor = 'text';
    var metric;
    var stringIdAccessor = 'id_str';

    var clipboard;
    var copyStatusButtonSelector = '#copy-status';
    var statusClipboardSelector = '#status-clipboard';

    var setUpClipboard = function () {
        clipboard = new Clipboard(copyStatusButtonSelector);
        clipboard.destroy();
        clipboard = new Clipboard(copyStatusButtonSelector);
    };

    var setUpExportAsCsv = function (timeSeries, $, fileSaver) {
        $('#export-as-csv').click(function () {
            var csvDocument = new CSV(timeSeries, {
                header: ['interest', 'date', 'text', 'link', 'screen_name']
            }).encode();
            fileSaver(new Blob([csvDocument], {type: 'text/csv'}), 'perspective.csv');
        });
    };

    if ('metric' in queryParams) {
        metric = queryParams.metric;
    } else {
        metric = 'retweet_count';
    }

    for (index = 1; index < totalDocuments; index++) {
        cell = hiddenCells[index];
        rawDocument = $(cell).text();
        try {
            status = JSON.parse(rawDocument);
        } catch (error) {
            if (debug) {
                logger.log('Could not parse raw document at index {}'.replace('{}', index));
            }
            regExp = /:(\s*)"([^"]*)"([^",]*)"([^"]*)"/g;
            escapedDocument = rawDocument.replace(regExp, ':$1"$2\\\"$3\\\"$4"');
            status = JSON.parse(escapedDocument);
        }

        if (metric in status) {
            var user = status.user;
            var statusId = status[stringIdAccessor];
            var link = 'https://twitter.com/' + user.screen_name + '/status/' + statusId;

            timeSeries.push({
                interest: status[metric],
                date: status[dateAccessor],
                text: status[textAccessor],
                link: link,
                name: user.name,
                screen_name: user.screen_name
            });
        }
    }

    if (timeSeries.length === 0) {
        return;
    }

    setUpExportAsCsv(JSON.parse(JSON.stringify(timeSeries)), $, fileSaver);
    timeSeries = MG.convert.date(timeSeries, 'date', '%a %b %e %H:%M:%S %Z %Y');

    MG.data_graphic({
        title: 'Perspective metrics',
        data: timeSeries,
        width: 1500,
        height: 500,
        right: 150,
        markers: [],
        mouseover: function(d) {
            var rows = [
                'Author: ' + d.name + ' (@' + d.screen_name + ')',
                'Status: ' + d.text,
                'Retweets: ' + d.interest
            ];
            $('#status').text(rows.join('\n'));

            $(statusClipboardSelector).val(d.link);
            $(copyStatusButtonSelector).attr('data-clipboard-text', d.link);
            $(statusClipboardSelector).focus();
        },
        target: document.getElementById('graph'),
        x_accessor: 'date',
        y_accessor: 'interest'
    });

    setUpClipboard();

})({
    Clipboard: window.Clipboard,
    CSV: window.CSV,
    logger: window.logger,
    MG: window.MG,
    document: window.document,
    fileSaver: window.saveAs,
    $: window.jQuery,
    queryParams: window.queryParams,
    debug: false
});
