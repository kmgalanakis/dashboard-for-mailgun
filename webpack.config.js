const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const StyleLintPlugin = require('stylelint-webpack-plugin');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const path = require( 'path' );

// Configuration for the ExtractTextPlugin.
const extractConfig = {
    use: [
        { loader: 'raw-loader' },
        {
            loader: 'postcss-loader',
            options: {
                plugins: [ require( 'autoprefixer' ) ]
            }
        },
        {
            loader: 'sass-loader',
            query: {
                outputStyle:
                    'production' === process.env.NODE_ENV ? 'compressed' : 'nested'
            }
        }
    ]
};

module.exports = function( env ) {
    return {
        entry: {
            './assets/js/mailgun_dashboard': './res/js/dashboard.js',
            './assets/js/mailgun_dashboard_settings': './res/js/settings.js'
        },
        output: {
            path: path.resolve( __dirname ),
            filename: '[name].js'
        },
        watch: true,
        devtool: 'source-map',
        module: {
            rules: [
                // Setup ESLint loader for JS.
                {
                    enforce: 'pre',
                    test: /\.js$/,
                    exclude: /node_modules/,
                    loader: 'eslint-loader',
                    options: {
                        emitWarning: true,
                    }
                },
                {
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'babel-loader',
                    },
                },
                {
                    test: /\.s?css$/,
                    use: ExtractTextPlugin.extract( extractConfig )
                }
            ]
        },
        plugins: [
            new ExtractTextPlugin({
                filename: './assets/css/mailgun_dashboard.css'
            }),
            new StyleLintPlugin({
                syntax: 'scss'
            }),
            // new UglifyJSPlugin({
            //     uglifyOptions: {
            //         mangle: {
            //             // Dont mangle these
            //             reserved: ['$super', '$', 'exports', 'require']
            //         }
            //     },
            //     sourceMap: true
            // }),
            new CopyWebpackPlugin([
                { from: './res/img/clouding-2x.gif', to: './assets/img' },
                // JS
                { from: './node_modules/bootstrap/dist/js/bootstrap.js', to: './assets/js/third-party' },
                { from: './node_modules/chart.js/dist/Chart.js', to: './assets/js/third-party' },
                { from: './node_modules/daterangepicker/moment.js', to: './assets/js/third-party'  },
                { from: './node_modules/daterangepicker/daterangepicker.js', to: './assets/js/third-party'  },
                { from: './node_modules/datatables.net/js/jquery.dataTables.js', to: './assets/js/third-party'  },
                // CSS
                { from: './node_modules/daterangepicker/daterangepicker.css', to: './assets/css/third-party'  },
                { from: './node_modules/font-awesome/css/font-awesome.min.css', to: './assets/css/third-party'  },
                { from: './node_modules/font-awesome/fonts', to: './assets/css/fonts'  },
                { from: './node_modules/bootstrap/dist/css/bootstrap.css', to: './assets/css/third-party'  },
                { from: './node_modules/datatables.net-dt/css/jquery.dataTables.css', to: './assets/css/third-party'  },
                { from: './node_modules/datatables.net-dt/images', to: './assets/css/images'  }
            ])
        ]
    }
};
