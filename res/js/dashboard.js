var MailgunDashboard_Dashboard = function( $ ) {

    var self = this;

    self.mgd_dashboard_action = 'mgd_get_mailgun_dashboard_api';

    self.getMailgunEvents = function() {
        var data = {
            action:	self.mgd_dashboard_action,
            type: 'events'
        };

        $.ajax( {
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                var logTableContainer = $( '#mgd-log-table-container' );
                if ( response.success ) {
                    var data = JSON.parse( response.data ).items;

                    var logTable = $( '#mgd-log-table' );
                    logTable.DataTable().destroy();
                    logTable.DataTable({
                        'data' : data,
                        'columns' : [
                            { defaultContent: '', width: '1%' },
                            { data: 'timestamp', width: '15%' },
                            {
                                data: 'event',
                                width: '15%',
                                render: function ( data, type, row, meta ) {
                                    var event = 'failed' !== row.event ? row.event : row.severity + '_' + row.event;
                                    return mailgun_dashboard_dashboard_texts.eventStatus[ event ];
                                },
                            },
                            { data: 'recipient', width: '68%' },
                        ],
                        'createdRow': function( row, data, dataIndex ) {
                            var event = 'failed' !== data.event ? data.event : data.severity + '_' + data.event;
                            $( row ).find( '>:first-child' ).addClass( 'status' ).addClass( event );
                        },
                        'iDisplayLength': 25,
                        'order': [[ 1, 'desc' ]],
                        'columnDefs': [
                            {
                                'targets': [0, 2],
                                'orderable': false
                            },
                            {
                                'type': 'date',
                                'targets': [1]
                            }
                        ],
                        bAutoWidth: false ,
                        'language' : {
                            'decimal': mailgun_dashboard_dashboard_texts.decimal,
                            'emptyTable': mailgun_dashboard_dashboard_texts.emptyTable,
                            'info': mailgun_dashboard_dashboard_texts.info,
                            'infoEmpty': mailgun_dashboard_dashboard_texts.infoEmpty,
                            'infoFiltered': mailgun_dashboard_dashboard_texts.infoFiltered,
                            'infoPostFix': mailgun_dashboard_dashboard_texts.infoPostFix,
                            'thousands': mailgun_dashboard_dashboard_texts.thousands,
                            'lengthMenu': mailgun_dashboard_dashboard_texts.lengthMenu,
                            'loadingRecords': mailgun_dashboard_dashboard_texts.loadingRecords,
                            'processing': mailgun_dashboard_dashboard_texts.processing,
                            'search': mailgun_dashboard_dashboard_texts.search,
                            'zeroRecords': mailgun_dashboard_dashboard_texts.zeroRecords,
                            'paginate': {
                                'first': mailgun_dashboard_dashboard_texts.first,
                                'last': mailgun_dashboard_dashboard_texts.last,
                                'next': mailgun_dashboard_dashboard_texts.next,
                                'previous': mailgun_dashboard_dashboard_texts.previous
                            },
                            'aria': {
                                'sortAscending': mailgun_dashboard_dashboard_texts.sortAscending,
                                'sortDescending': mailgun_dashboard_dashboard_texts.sortDescending
                            }
                        }
                    });
                    logTableContainer.fadeIn();

                    $( '.js-mgd-refresh-dashboard' ).fadeIn();
                } else {
                    self.consoleWarnErrors( response.data );
                }

                logTableContainer.prev( '.mgd-loading-section' ).fadeOut();
            }
        });
    };

    $( '#mgd-log-table tbody' ).on( 'click', 'tr', function () {
        var logTable = $( '#mgd-log-table' ).DataTable();
        var tr = $( this ).closest( 'tr' );
        var row = logTable.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass( 'shown' );
        }
        else {
            // Open this row
            row.child( self.formatChildRow( row.data() ) ).show();
            tr.addClass( 'shown' );
        }
    } );

    self.formatChildRow = function( data ) {
        return '<pre>' + JSON.stringify( data, null, '\t' ) + '</pre>';
    }

    self.getMailgunStats = function() {
        var data = {
            action:	self.mgd_dashboard_action,
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
            ]
        };

        $.ajax( {
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                if ( response.success ) {
                    self.drawChart( JSON.parse( response.data ), data['events'] );
                } else {
                    self.consoleWarnErrors( response.data );
                }
            }
        });
    };

    self.getMailgunLog = function() {
        var data = {
            action:	self.mgd_dashboard_action,
            type: 'log'
        };

        $.ajax( {
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                var logTableContainer = $( '#mgd-log-table-container' );
                if ( response.success ) {
                    var logTable = $( '#mgd-log-table' ),
                        logTableBody = $( '#mgd-log-table tbody' );

                    logTable.DataTable().destroy();
                    logTableBody.empty();

                    $.each( JSON.parse( response.data ).items, function( index, value ) {
                        var newRow = '<tr>' +
                                        '<td class="status ' + value.hap + '" title="' + mailgun_dashboard_dashboard_texts.eventStatus[ value.hap ] + '"></td>' +
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
                            'order': [[ 1, 'desc' ]],
                            'columnDefs': [
                                {
                                    'targets': [0, 2],
                                    'orderable': false
                                },
                                {
                                    'type': 'date',
                                    'targets': [1]
                                }
                            ],
                            bAutoWidth: false ,
                            aoColumns : [
                                { sWidth: '1%' },
                                { sWidth: '15%' },
                                { sWidth: '73%' }
                            ],
                            'language' : {
                                'decimal': mailgun_dashboard_dashboard_texts.decimal,
                                'emptyTable': mailgun_dashboard_dashboard_texts.emptyTable,
                                'info': mailgun_dashboard_dashboard_texts.info,
                                'infoEmpty': mailgun_dashboard_dashboard_texts.infoEmpty,
                                'infoFiltered': mailgun_dashboard_dashboard_texts.infoFiltered,
                                'infoPostFix': mailgun_dashboard_dashboard_texts.infoPostFix,
                                'thousands': mailgun_dashboard_dashboard_texts.thousands,
                                'lengthMenu': mailgun_dashboard_dashboard_texts.lengthMenu,
                                'loadingRecords': mailgun_dashboard_dashboard_texts.loadingRecords,
                                'processing': mailgun_dashboard_dashboard_texts.processing,
                                'search': mailgun_dashboard_dashboard_texts.search,
                                'zeroRecords': mailgun_dashboard_dashboard_texts.zeroRecords,
                                'paginate': {
                                    'first': mailgun_dashboard_dashboard_texts.first,
                                    'last': mailgun_dashboard_dashboard_texts.last,
                                    'next': mailgun_dashboard_dashboard_texts.next,
                                    'previous': mailgun_dashboard_dashboard_texts.previous
                                },
                                'aria': {
                                    'sortAscending': mailgun_dashboard_dashboard_texts.sortAscending,
                                    'sortDescending': mailgun_dashboard_dashboard_texts.sortDescending
                                }
                            }
                        }
                    );

                    logTableContainer.fadeIn();

                    $( '.js-mgd-refresh-dashboard' ).fadeIn();
                } else {
                    self.consoleWarnErrors( response.data );
                }

                logTableContainer.prev( '.mgd-loading-section' ).fadeOut();
            }
        });
    };

    self.consoleWarnErrors = function( data ) {
        $.each( data, function( key, value ) {
            alert( mailgun_dashboard_dashboard_texts.mailgun_api_failed + '!' + '\n' + mailgun_dashboard_dashboard_texts.console_for_info + '.' );
            console.warn( mailgun_dashboard_dashboard_texts.mailgun_api_error + ': ' +  key + ' => ' + value );
        });
    };

    self.drawChart = function( data, events ) {
        var statsTableContainer = $( '#mgd-log-stats-chart' ),
            datasets = [],
            labels = [],
            accepted = [],
            delivered = [],
            opened = [],
            clicked = [],
            complained = [],
            unsubscribed = [],
            temporary_fail = [],
            permanent_fail = [],
            stored = [];

        statsTableContainer.fadeIn();

        $.each( data.stats, function( index, element ) {
            labels.push( element.time );
            accepted.push( element.accepted.total );
            delivered.push( element.delivered.total );
            opened.push( element.opened.total );
            clicked.push( element.clicked.total );
            complained.push( element.complained.total );
            unsubscribed.push( element.unsubscribed.total );
            temporary_fail.push( element.failed.temporary.espblock );
            permanent_fail.push( element.failed.permanent.total );
            stored.push( element.stored.total );
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'accepted' ],
            backgroundColor: '#5da5da',
            data: accepted
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'delivered' ],
            backgroundColor: '#60bd68',
            data: delivered
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'opened' ],
            backgroundColor: '#407bbf',
            data: opened
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'clicked' ],
            backgroundColor: '#386F3F',
            data: clicked
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'stored' ],
            backgroundColor: '#2A6062',
            data: stored
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'complained' ],
            backgroundColor: '#333333',
            data: complained
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'unsubscribed' ],
            backgroundColor: '#BF9940',
            data: unsubscribed
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'temporary_failed' ],
            backgroundColor: '#FF9800',
            data: temporary_fail
        });

        datasets.push( {
            label: mailgun_dashboard_dashboard_texts.eventStatus[ 'permanent_failed' ],
            backgroundColor: '#C40022',
            data: permanent_fail
        });

        var barChartData = {
            'labels': labels,
            'datasets': datasets
        };

        var ctx = document.getElementById( 'canvas' );
        window.myBar = new Chart( ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                title:{
                    display: true,
                    text: mailgun_dashboard_dashboard_texts.chartTitle
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
        $( '.js-mgd-refresh-dashboard' ).fadeIn();
        statsTableContainer.prev( '.mgd-loading-section' ).fadeOut();
    };

    $( document ).on( 'click', '.js-mgd-refresh-dashboard-button', function() {
        var statsTableContainer = $( '#mgd-log-stats-chart' );
        statsTableContainer.prev( '.mgd-loading-section' ).fadeIn();
        statsTableContainer.fadeOut();

        var logTableContainer = $( '#mgd-log-table-container' );
        logTableContainer.prev( '.mgd-loading-section' ).fadeIn();
        logTableContainer.fadeOut();

        $( '.js-mgd-refresh-dashboard') .fadeOut();

        // self.getMailgunLog();
        self.getMailgunEvents();
        self.getMailgunStats();
    });

    self.init = function() {
        // self.getMailgunLog();
        self.getMailgunEvents();
        self.getMailgunStats();
    };

    self.init();

};

jQuery( document ).ready( function( $ ) {
    var dashboardInstance = new MailgunDashboard_Dashboard( $ );
});