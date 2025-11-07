import { registerTrainingSettings } from './settings/training';

registerTrainingSettings();

// Remove user autocomplete list from the editor
wp.domReady(() => {
	wp.hooks.removeFilter(
		'editor.Autocomplete.completers',
		'editor/autocompleters/set-default-completers'
	);
});
