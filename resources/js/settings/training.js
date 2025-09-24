import {
	DateTimePicker,
	PanelBody,
	TextControl,
	TextareaControl,
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
	const trainingType = 'training_type';
	const themeColor = 'training_theme_color';
	const registrationDeadline = 'training_registration_deadline';
	const registrationUrl = 'training_registration_url';
	const moreInfo = 'training_more_info';
	const trainingSchedule = 'training_schedule';

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
				<PanelBody title="Teema" initialOpen={false}>
					<SelectControl
						label="Valitse teema"
						value={getPostMeta(themeColor) || 'suomenlinna'} // if meta empty use suomenlinna
						options={[
							{ label: 'Suomenlinna', value: 'suomenlinna' },
							{ label: 'Kupari', value: 'copper' },
							{ label: 'Engel', value: 'engel' },
							{ label: 'Bussi', value: 'bus' },
							{ label: 'Vaakuna', value: 'coat-of-arms' },
						]}
						onChange={(value) => setPostMeta(themeColor, value)}
					/>
				</PanelBody>

				<PanelBody title="Tapahtuman tyyppi" initialOpen={false}>
					<SelectControl
						label="Valitse tapahtuman tyyppi"
						value={getPostMeta(trainingType) || 'onsite'} // if meta empty use light
						options={[
							{ label: 'Lähikoulutus', value: 'onsite' },
							{ label: 'Hybridikoulutus', value: 'hybrid' },
							{ label: 'Verkkokoulutus', value: 'online' },
						]}
						onChange={(value) => setPostMeta(trainingType, value)}
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
				<PanelBody title="Ajankohta" initialOpen={false}>
					<TextControl
						label={'Ajankohta (päivä(t) ja aika)'}
						value={getPostMeta(trainingSchedule)}
						placeholder={'Pe klo 12-16'}
						onChange={(value) =>
							setPostMeta(trainingSchedule, value)
						}
					/>
				</PanelBody>
				<PanelBody title="Ilmoittautuminen loppuu" initialOpen={false}>
					<DateTimePicker
						id="training_registration_deadline"
						currentDate={getPostMeta(registrationDeadline)}
						onChange={(value) =>
							setPostMeta(registrationDeadline, value)
						}
						startOfWeek={1}
					/>
				</PanelBody>
				<PanelBody title="Ilmoittautumisen URL" initialOpen={false}>
					<TextControl
						label={
							'Kirjoita ilmoittautumisen URL, jos sellainen on olemassa'
						}
						value={getPostMeta(registrationUrl)}
						onChange={(value) =>
							setPostMeta(registrationUrl, value)
						}
					/>
				</PanelBody>
				<PanelBody title="Lisätiedot" initialOpen={false}>
					<TextareaControl
						label={'Koulutuksen lisätiedot'}
						value={getPostMeta(moreInfo)}
						onChange={(value) => setPostMeta(moreInfo, value)}
						help={
							'Nämä lisätiedot näkyvät koulutuksen sivulla sivupalkissa.'
						}
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
			</PluginSidebar>
		</>
	);
};

export const registerTrainingSettings = () => {
	registerPlugin('opehuone-training-settings', {
		render: editorSettings,
	});
};
