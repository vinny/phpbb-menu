import js from "@eslint/js";
import globals from "globals";
import html from "eslint-plugin-html";

export default [
	js.configs.recommended,
	{
		ignores: [
			"adm/style/js/Sortable.min.js",
			"vendor/**",
			"node_modules/**"
		],
	},
	{
		files: ["**/*.js", "**/*.html"],
		plugins: {
			html: html,
		},
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: "script",
			globals: {
				...globals.browser,
				...globals.jquery,
				phpbb: "readonly",
				Sortable: "readonly",
			},
		},
		rules: {
			"indent": ["error", "tab", { SwitchCase: 1 }],
			"quotes": ["error", "single", { avoidEscape: true }],
			"semi": ["error", "always"],
			"no-unused-vars": ["error", { argsIgnorePattern: "^_" }],
		},
	},
];
