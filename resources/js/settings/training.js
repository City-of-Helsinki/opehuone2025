import {
	DateTimePicker,
	PanelBody,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/editor';
import { cog } from '@wordpress/icons';

const editorSettings = () => {
	const postTypes = ['training'];

	const postType = useSelect(
		(select) => select('core/editor').getCurrentPostType(),
		[]
	);

	const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

	const showSettings = postTypes.includes(postType);
	if (!showSettings) {
		return null;
	}

	// Meta fields to target
	const startTime = 'training_start_datetime';
	const endTime = 'training_end_datetime';
	const draftTime = 'training_draft_datetime';
	const isOnlineTraining = 'training_is_online';
	const themeColor = 'training_theme_color';

	const getPostMeta = (key) => meta[key] || '';

	const setPostMeta = (key, value) =>
		setMeta({
			...meta,
			[key]: value,
		});

	return (
		<>
			<PluginSidebarMoreMenuItem
				target="opehuone-training-setting-sidebar"
				icon={cog}
			>
				{'Koulutuksen asetukset'}
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="opehuone-training-setting-sidebar"
				icon={cog}
				title="Koulutuksen asetukset"
			>
				<PanelBody title="Verkkokoulutus?" initialOpen={false}>
					<ToggleControl
						label="Onko tämä verkkokoulutus?"
						checked={getPostMeta(isOnlineTraining)}
						onChange={(value) => {
							setPostMeta(isOnlineTraining, value);
						}}
					/>
				</PanelBody>
				<PanelBody title="Aloitusaika" initialOpen={false}>
					<DateTimePicker
						id="training_start_datetime"
						currentDate={getPostMeta(startTime)}
						onChange={(value) => setPostMeta(startTime, value)}
						startOfWeek={1}
					/>
				</PanelBody>
				<PanelBody title="Loppumisaika" initialOpen={false}>
					<DateTimePicker
						id="training_end_datetime"
						currentDate={getPostMeta(endTime)}
						onChange={(value) => setPostMeta(endTime, value)}
						startOfWeek={1}
					/>
				</PanelBody>
				<PanelBody
					title="Autommaattinen luonnos tämän päivän jälkeen"
					initialOpen={false}
				>
					<DateTimePicker
						id="training_draft_datetime"
						currentDate={getPostMeta(draftTime)}
						onChange={(value) => setPostMeta(draftTime, value)}
						startOfWeek={1}
					/>
				</PanelBody>
				<PanelBody title="Teema" initialOpen={false}>
					<SelectControl
						label="Valitse teema"
						value={getPostMeta(themeColor) || 'light'} // if meta empty use light
						options={[
							{ label: 'Vaalea', value: 'light' },
							{ label: 'Tumma', value: 'dark' },
						]}
						onChange={(value) => setPostMeta(themeColor, value)}
					/>
				</PanelBody>
			</PluginSidebar>
		</>
	);
};

export const registerTrainingSettings = () => {
	registerPlugin('opehuone-training-settings', {
		render: editorSettings,
	});
};
