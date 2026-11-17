import {
	Button,
	DateTimePicker,
	PanelBody,
	TextControl,
	TextareaControl,
	SelectControl,
	ToggleControl,
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
	const showTime = 'training_show_time';
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

	const extraDatesKey = 'training_extra_dates';

	let extraDates = [];
	try {
		const parsed = JSON.parse(meta[extraDatesKey] || '[]');
		extraDates = Array.isArray(parsed) ? parsed : [];
	} catch (e) {
		extraDates = [];
	}

	const setExtraDates = (next) =>
		setPostMeta(extraDatesKey, JSON.stringify(next));

	const addExtraDate = () =>
		setExtraDates([...extraDates, { start: '', end: '' }]);

	const updateExtraDate = (index, field, value) =>
		setExtraDates(
			extraDates.map((d, i) =>
				i === index ? { ...d, [field]: value } : d
			)
		);

	const removeExtraDate = (index) =>
		setExtraDates(extraDates.filter((_, i) => i !== index));

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
				<div style={{ padding: '16px' }}>
					<ToggleControl
						label="Näytä kellonaika"
						checked={getPostMeta(showTime) === '1'}
						onChange={(value) =>
							setPostMeta(showTime, value ? '1' : '')
						}
						help="Kun päällä, sivulla näytetään päivämäärän lisäksi kellonaika. Kun pois päältä, näytetään vain päivämäärä."
						__nextHasNoMarginBottom
					/>
				</div>
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
					title={
						extraDates.length
							? `Lisäpäivämäärät (${extraDates.length})`
							: 'Lisäpäivämäärät'
					}
					initialOpen={extraDates.length > 0}
				>
					{extraDates.map((date, index) => (
						<div
							key={index}
							style={{
								marginBottom: '16px',
								border: '1px solid #ddd',
							}}
						>
							<PanelBody
								title={`Aloitusaika ${index + 2}`}
								initialOpen={false}
							>
								<DateTimePicker
									currentDate={date.start || null}
									onChange={(value) =>
										updateExtraDate(index, 'start', value)
									}
									startOfWeek={1}
								/>
							</PanelBody>
							<PanelBody
								title={`Loppumisaika ${index + 2}`}
								initialOpen={false}
							>
								<DateTimePicker
									currentDate={date.end || null}
									onChange={(value) =>
										updateExtraDate(index, 'end', value)
									}
									startOfWeek={1}
								/>
							</PanelBody>
							<div style={{ padding: '8px' }}>
								<Button
									variant="link"
									isDestructive
									onClick={() => removeExtraDate(index)}
								>
									Poista päivämäärä {index + 2}
								</Button>
							</div>
						</div>
					))}
					<Button variant="secondary" onClick={addExtraDate}>
						+ Lisää koulutuksen päivämäärät
					</Button>
				</PanelBody>
				<PanelBody title="Ajankohta, lisätiedot" initialOpen={false}>
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
