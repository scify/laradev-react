import js from '@eslint/js';
import prettier from 'eslint-config-prettier';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import * as typescript from '@typescript-eslint/eslint-plugin';
import * as parser from '@typescript-eslint/parser';
import { Linter } from 'eslint';

/** @type {Linter.Config[]} */
const config: Linter.Config[] = [
	// JavaScript base config
	js.configs.recommended,

	// TypeScript linting with a Flat Config compatible version
	{
		languageOptions: {
			parser: parser,
			parserOptions: {
				project: './tsconfig.json', // Ensure TypeScript files are properly linted
				sourceType: 'module',
			},
		},
		plugins: {
			'@typescript-eslint': typescript,
		},
		rules: {
			...typescript.configs['recommended-type-checked']?.rules,
		},
	},

	// React linting with Flat Config
	{
		languageOptions: {
			globals: {
				...globals.browser,
				route: true,
			},
		},
		plugins: {
			react,
		},
		rules: {
			...react.configs.flat.recommended?.rules,
			...react.configs.flat['jsx-runtime']?.rules,
			'react/react-in-jsx-scope': 'off', // Not needed in React 17+
			'react/prop-types': 'off', // Not needed when using TypeScript
			'react/no-unescaped-entities': 'off', // Prevents issues with JSX & special characters
		},
		settings: {
			react: {
				version: 'detect',
			},
		},
	},

	// React Hooks linting
	{
		plugins: {
			'react-hooks': reactHooks,
		},
		rules: {
			'react-hooks/rules-of-hooks': 'error', // Ensure hooks are used correctly
			'react-hooks/exhaustive-deps': 'warn', // Warn about missing dependencies in useEffect
		},
	},

	// Ignore unnecessary directories
	{
		files: ['**/*.ts', '**/*.tsx', '**/*.js', '**/*.jsx'],
		ignores: [
			'vendor',
			'node_modules',
			'public',
			'bootstrap/ssr',
			'tailwind.config.js',
			'postcss.config.js',
			'prettier.config.cjs',
			'eslint.config.ts',
			'resources/js/ziggy.js',
			'vite.config.ts',
		],
	},

	// Apply Prettier last to avoid conflicts
	prettier,
];

export default config;
