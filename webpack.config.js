const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

let config = {
    entry: {
		'admin': ['./resources/js/admin.ts', './resources/scss/instagram.scss'],
        'front': ['./resources/js/front.ts', './resources/scss/instagram.scss'],
    },
    output: {
        path: path.resolve(__dirname, './views'),
        filename: 'js/[name].js',
    },
    module: {
        rules: [
            {
				test: /\.tsx?$/,
				use: 'ts-loader',
				exclude: /node_modules/,
			},
            {
                test: /\.(png|jpg|gif|woff|woff2|ttf|eot|svg)$/,
                loader: 'ignore-loader',
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                            url: false,
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                path: 'postcss.config.js',
                            },
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
        ],
    },
	resolve: {
		extensions: ['.tsx', '.ts', '.js'],
	},
    externals: {},
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/[name].css',
        }),
    ],
    devtool: 'inline-source-map',
    watchOptions: {
        ignored: /node_modules/,
    },
};

module.exports = config;