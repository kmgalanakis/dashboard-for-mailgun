import './../css/mailgun_dashboard.scss';

var MailgunDashboardDashboard = function( $ ) {

    var self = this;

    self.mgdDashboardAction = 'mgd_get_mailgun_dashboard_api';

    self.getMailgunEvents = function( dateRangeStart, dateRangeEnd ) {
        var data = {
            action:	self.mgdDashboardAction,
            type: 'events',
            dateRangeStart: dateRangeStart.unix(),
            dateRangeEnd: dateRangeEnd.unix()
        },
            logTable;

        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                var logTableContainer = $( '#mgd-events-table-container' );
                if ( response.success ) {
                    data = JSON.parse( response.data ).items;
                    logTable = $( '#mgd-events-table' );
                    logTable.DataTable().destroy();
                    logTable.DataTable({
                        'data': data,
                        'columns': [
                            { defaultContent: '', width: '1%' },
                            { data: 'timestamp', width: '15%' },
                            {
                                data: 'event',
                                width: '15%',
                                render: function( data, type, row, meta ) {
                                    var event = 'failed' !== row.event ? row.event : row.severity + '_' + row.event;
                                    return mailgunDashboardDashboardTexts.eventStatus[ event ];
                                }
                            },
                            { data: 'recipient', width: '68%' }
                        ],
                        'createdRow': function( row, data, dataIndex ) {
                            var event = 'failed' !== data.event ? data.event : data.severity + '_' + data.event;
                            $( row ).find( '>:first-child' ).addClass( 'status' ).addClass( event );
                        },
                        'iDisplayLength': 25,
                        'order': [ [ 1, 'desc' ] ],
                        'columnDefs': [
                            {
                                'targets': [ 0, 2 ],
                                'orderable': false
                            },
                            {
                                'type': 'date',
                                'targets': [ 1 ]
                            }
                        ],
                        bAutoWidth: false,
                        'language': {
                            'decimal': mailgunDashboardDashboardTexts.decimal,
                            'emptyTable': mailgunDashboardDashboardTexts.emptyTable,
                            'info': mailgunDashboardDashboardTexts.info,
                            'infoEmpty': mailgunDashboardDashboardTexts.infoEmpty,
                            'infoFiltered': mailgunDashboardDashboardTexts.infoFiltered,
                            'infoPostFix': mailgunDashboardDashboardTexts.infoPostFix,
                            'thousands': mailgunDashboardDashboardTexts.thousands,
                            'lengthMenu': mailgunDashboardDashboardTexts.lengthMenu,
                            'loadingRecords': mailgunDashboardDashboardTexts.loadingRecords,
                            'processing': mailgunDashboardDashboardTexts.processing,
                            'search': mailgunDashboardDashboardTexts.search,
                            'zeroRecords': mailgunDashboardDashboardTexts.zeroRecords,
                            'paginate': {
                                'first': mailgunDashboardDashboardTexts.first,
                                'last': mailgunDashboardDashboardTexts.last,
                                'next': mailgunDashboardDashboardTexts.next,
                                'previous': mailgunDashboardDashboardTexts.previous
                            },
                            'aria': {
                                'sortAscending': mailgunDashboardDashboardTexts.sortAscending,
                                'sortDescending': mailgunDashboardDashboardTexts.sortDescending
                            }
                        }
                    });

                    // Fade out the loading gif
                    logTableContainer.prev( '.mgd-loading-section' ).fadeOut();

                    // Fade in the table
                    logTableContainer.fadeIn();

                    // Fade in the refresh dashboard button
                    $( '.js-mgd-refresh-dashboard' ).fadeIn();
                } else {
                    self.consoleWarnErrors( response.data );
                }
            }
        });
    };

    $( '#mgd-events-table tbody' ).on( 'click', 'tr', function() {
        var logTable = $( '#mgd-events-table' ).DataTable();
        var tr = $( this ).closest( 'tr' );
        var row = logTable.row( tr );

        if ( row.child.isShown() ) {

            // This row is already open - close it
            row.child.hide();
            tr.removeClass( 'shown' );
        } else {

            // Open this row
            row.child( self.formatChildRow( row.data() ) ).show();
            tr.addClass( 'shown' );
        }
    });

    self.formatChildRow = function( data ) {
        return '<pre>' + JSON.stringify( data, null, '\t' ) + '</pre>';
    };

    self.getMailgunStats = function( dateRangeStart, dateRangeEnd ) {
        var data = {
            action:	self.mgdDashboardAction,
            type: 'stats',
            resolution: 'hour',
            events: [
                'delivered',
                'failed',
                'stored',
                'accepted',
                'opened',
                'clicked',
                'unsubscribed',
                'complained'
            ],
            dateRangeStart: dateRangeStart.unix(),
            dateRangeEnd: dateRangeEnd.unix()
        };

        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                if ( response.success ) {
                    self.drawChart( JSON.parse( response.data ), data.events );
                } else {
                    self.consoleWarnErrors( response.data );
                }
            }
        });
    };

    self.getMailgunLog = function() {
        var data = {
            action:	self.mgdDashboardAction,
            type: 'log'
        },
        logTable,
        logTableBody;

        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                var logTableContainer = $( '#mgd-events-table-container' );
                if ( response.success ) {
                    logTable = $( '#mgd-events-table' );
                    logTableBody = $( '#mgd-events-table tbody' );

                    logTable.DataTable().destroy();
                    logTableBody.empty();

                    $.each( JSON.parse( response.data ).items, function( index, value ) {
                        var newRow = '<tr>' +
                                        '<td class="status ' + value.hap + '" title="' + mailgunDashboardDashboardTexts.eventStatus[ value.hap ] + '"></td>' +
                                        '<td>' +
                                            value.created_at +
                                        '</td>' +
                                        '<td>' +
                                            value.message +
                                        '</td>' +
                                    '</tr>';
                        logTableBody.append( newRow );
                    });

                    logTable.DataTable(
                        {
                            'iDisplayLength': 25,
                            'order': [ [ 1, 'desc' ] ],
                            'columnDefs': [
                                {
                                    'targets': [ 0, 2 ],
                                    'orderable': false
                                },
                                {
                                    'type': 'date',
                                    'targets': [ 1 ]
                                }
                            ],
                            bAutoWidth: false,
                            aoColumns: [
                                { sWidth: '1%' },
                                { sWidth: '15%' },
                                { sWidth: '73%' }
                            ],
                            'language': {
                                'decimal': mailgunDashboardDashboardTexts.decimal,
                                'emptyTable': mailgunDashboardDashboardTexts.emptyTable,
                                'info': mailgunDashboardDashboardTexts.info,
                                'infoEmpty': mailgunDashboardDashboardTexts.infoEmpty,
                                'infoFiltered': mailgunDashboardDashboardTexts.infoFiltered,
                                'infoPostFix': mailgunDashboardDashboardTexts.infoPostFix,
                                'thousands': mailgunDashboardDashboardTexts.thousands,
                                'lengthMenu': mailgunDashboardDashboardTexts.lengthMenu,
                                'loadingRecords': mailgunDashboardDashboardTexts.loadingRecords,
                                'processing': mailgunDashboardDashboardTexts.processing,
                                'search': mailgunDashboardDashboardTexts.search,
                                'zeroRecords': mailgunDashboardDashboardTexts.zeroRecords,
                                'paginate': {
                                    'first': mailgunDashboardDashboardTexts.first,
                                    'last': mailgunDashboardDashboardTexts.last,
                                    'next': mailgunDashboardDashboardTexts.next,
                                    'previous': mailgunDashboardDashboardTexts.previous
                                },
                                'aria': {
                                    'sortAscending': mailgunDashboardDashboardTexts.sortAscending,
                                    'sortDescending': mailgunDashboardDashboardTexts.sortDescending
                                }
                            }
                        }
                    );

                    // Fade out the loading gif
                    logTableContainer.prev( '.mgd-loading-section' ).fadeOut();

                    // Fade in the table
                    logTableContainer.fadeIn();

                    // Fade in the refresh dashboard button
                    $( '.js-mgd-refresh-dashboard' ).fadeIn();
                } else {
                    self.consoleWarnErrors( response.data );
                }


            }
        });
    };

    self.consoleWarnErrors = function( data ) {
        $.each( data, function( key, value ) {
            alert( mailgunDashboardDashboardTexts.mailgun_api_failed + '!' + '\n' + mailgunDashboardDashboardTexts.console_for_info + '.' );
            console.warn( mailgunDashboardDashboardTexts.mailgun_api_error + ': ' +  key + ' => ' + value );
        });
    };

    self.drawChart = function( data, events ) {
        var statsChartContainer = $( '#mgd-stats-chart' ),
            dateRangeContainer = $( '#mgd-date-range' ),
            datasets = [],
            labels = [],
            accepted = [],
            delivered = [],
            opened = [],
            clicked = [],
            complained = [],
            unsubscribed = [],
            temporaryFail = [],
            permanentFail = [],
            stored = [],
            barChartData,
            ctx;

        $.each( data.stats, function( index, element ) {
            labels.push( element.time );
            accepted.push( element.accepted.total );
            delivered.push( element.delivered.total );
            opened.push( element.opened.total );
            clicked.push( element.clicked.total );
            complained.push( element.complained.total );
            unsubscribed.push( element.unsubscribed.total );
            temporaryFail.push( element.failed.temporary.espblock );
            permanentFail.push( element.failed.permanent.total );
            stored.push( element.stored.total );
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.accepted,
            backgroundColor: '#5da5da',
            data: accepted
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.delivered,
            backgroundColor: '#60bd68',
            data: delivered
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.opened,
            backgroundColor: '#407bbf',
            data: opened
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.clicked,
            backgroundColor: '#386F3F',
            data: clicked
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.stored,
            backgroundColor: '#2A6062',
            data: stored
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.complained,
            backgroundColor: '#333333',
            data: complained
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.unsubscribed,
            backgroundColor: '#BF9940',
            data: unsubscribed
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.temporary_failed,
            backgroundColor: '#FF9800',
            data: temporaryFail
        });

        datasets.push({
            label: mailgunDashboardDashboardTexts.eventStatus.permanent_failed,
            backgroundColor: '#C40022',
            data: permanentFail
        });

        barChartData = {
            'labels': labels,
            'datasets': datasets
        };

        if ( 'undefined' !== typeof window.myBar ) {
            window.myBar.destroy();
        }

        ctx = document.getElementById( 'canvas' );
        window.myBar = new Chart( ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                title: {
                    display: true,
                    text: mailgunDashboardDashboardTexts.chartTitle
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [ { stacked: true } ],
                    yAxes: [ { stacked: true } ]
                }
            }
        });

        // Fade out the loading gif
        statsChartContainer.prev( '.mgd-loading-section' ).fadeOut();

        // Fade in the chart
        statsChartContainer.fadeIn();

        // Fade in the date range picker
        dateRangeContainer.fadeIn();

        // Fade in the refresh dashboard button
        $( '.js-mgd-refresh-dashboard' ).fadeIn();
    };

    $( document ).on( 'click', '.js-mgd-refresh-dashboard-button', function() {
        var range,
            statsChartContainer,
            dateRangeContainer,
            eventsTableContainer;

        dateRangeContainer = $( '#mgd-date-range' );
        dateRangeContainer.fadeOut();

        statsChartContainer = $( '#mgd-stats-chart' );
        statsChartContainer.prev( '.mgd-loading-section' ).fadeIn();
        statsChartContainer.fadeOut();

        eventsTableContainer = $( '#mgd-events-table-container' );
        eventsTableContainer.prev( '.mgd-loading-section' ).fadeIn();
        eventsTableContainer.fadeOut();

        $( '.js-mgd-refresh-dashboard' ) .fadeOut();

        range = self.setRangeStartEnd();

        self.setDateRangeValue( range.dateRangeStart, range.dateRangeEnd );

        // self.getMailgunLog();
        self.getMailgunStats( range.dateRangeStart, range.dateRangeEnd );
        self.getMailgunEvents( range.dateRangeStart, range.dateRangeEnd );
    });

    self.setDateRangeValue = function( dateRangeStart, dateRangeEnd ) {
        $( '#mgd-date-range span' ).html( dateRangeStart.format( 'MMMM D, YYYY' ) + ' - ' + dateRangeEnd.format( 'MMMM D, YYYY' ) );
    };

    self.dateRangeCallback = function( dateRangeStart, dateRangeEnd ) {

        var eventsTableContainer,
            statsChartContainer;

        self.setDateRangeValue( dateRangeStart, dateRangeEnd );

        statsChartContainer = $( '#mgd-stats-chart' );
        statsChartContainer.prev( '.mgd-loading-section' ).fadeIn();
        statsChartContainer.fadeOut();

        self.getMailgunStats( dateRangeStart, dateRangeEnd );

        eventsTableContainer = $( '#mgd-events-table-container' );
        eventsTableContainer.prev( '.mgd-loading-section' ).fadeIn();
        eventsTableContainer.fadeOut();

        self.getMailgunEvents( dateRangeStart, dateRangeEnd );
    };

    self.initDatePicker = function( dateRangeStart, dateRangeEnd ) {
        var ranges = {};

        ranges[ mailgunDashboardDashboardTexts.today ] = [ moment(), moment() ];
        ranges[ mailgunDashboardDashboardTexts.yesterday ] = [ moment().subtract( 1, 'days' ), moment().subtract( 1, 'days' ) ];
        ranges[ mailgunDashboardDashboardTexts.lastSevenDays ] = [ moment().subtract( 6, 'days' ), moment() ];
        ranges[ mailgunDashboardDashboardTexts.lastTwentyEightDays ] = [ moment().subtract( 27, 'days' ), moment() ];

        $( '#mgd-date-range' ).daterangepicker({
            alwaysShowCalendars: true,
            minDate: moment().subtract( 27, 'days' ),
            maxDate: moment(),
            ranges: ranges
        }, self.dateRangeCallback );

        self.setDateRangeValue( dateRangeStart, dateRangeEnd );
    };

    self.setRangeStartEnd = function() {
        return {
            dateRangeStart: moment().subtract( 6, 'days' ),
            dateRangeEnd: moment()
        };
    };

    self.init = function() {
        var range = self.setRangeStartEnd();

        // self.getMailgunLog();
        self.getMailgunStats( range.dateRangeStart, range.dateRangeEnd );
        self.initDatePicker( range.dateRangeStart, range.dateRangeEnd );
        self.getMailgunEvents( range.dateRangeStart, range.dateRangeEnd );
    };

    self.init();

};

jQuery( document ).ready( function( $ ) {
    var dashboardInstance = new MailgunDashboardDashboard( $ );
});
