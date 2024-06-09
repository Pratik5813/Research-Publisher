/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, MediaPlaceholder } from "@wordpress/block-editor";
import { PanelBody, TextControl, DatePicker } from "@wordpress/components";
import { useState } from "react";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const [isCollapsed, setIsCollapsed] = useState(
		attributes.isPanelCollapsed || false,
	);
	const panelBodyClassName = isCollapsed
		? "research-details-panel-borderless"
		: "research-details-panel";

	function updateResearchTitle(newTitle) {
		setAttributes({ researchTitle: newTitle });
	}
	function changeAuthors(newAuthors) {
		setAttributes({ authors: newAuthors });
	}
	function changeAuthorImages(newImages) {
		// If newImages is empty, set authorImages to an empty array
		if (!newImages || newImages.length === 0) {
			setAttributes({ authorImages: [] });
		} else {
			// If newImages is an array, use it directly
			const imagesArray = Array.isArray(newImages) ? newImages : [newImages];
			setAttributes({ authorImages: imagesArray });
		}
		// Enable the select button if it exists
		const selectButton = document.querySelector(".media-button");
		if (selectButton) {
			selectButton.disabled = false;
		}
	}
	function removeAuthorImage(index) {
		const newImages = [...attributes.authorImages];
		newImages.splice(index, 1);
		setAttributes({ authorImages: newImages });
	}
	function changeDOINumber(newDOI) {
		setAttributes({ doiNumber: newDOI });
	}
	function changeAbstractLink(newAbstractLink) {
		setAttributes({ abstractLink: newAbstractLink });
	}
	function changeDownloadLink(newDownloadLink) {
		setAttributes({ downloadLink: newDownloadLink });
	}
	function toggleCollapse() {
		const newIsCollapsed = !isCollapsed;
		setIsCollapsed(newIsCollapsed);
		setAttributes({ isPanelCollapsed: newIsCollapsed });
	}
	return (
		<div {...useBlockProps()}>
			{__(
				<>
					<PanelBody
						title={`Research Details for ${attributes.researchTitle}`}
						initialOpen={!isCollapsed} // Controls the initial open/close state
						onToggle={toggleCollapse} // Function to toggle the open/close state
						className={panelBodyClassName}
					>
						<h3>Add Research</h3>
						<TextControl
							label="Manuscript Title:"
							value={attributes.researchTitle}
							onChange={updateResearchTitle}
						/>
						<h3>Add Authors</h3>
						<TextControl
							label="Authors:"
							value={attributes.authors}
							onChange={changeAuthors}
						></TextControl>
						<h3>Add Author Images</h3>
						<div
							className="selected-images"
							style={{ display: "flex", marginBottom: "10px" }}
						>
							{attributes.authorImages &&
								attributes.authorImages.map((image, index) => (
									<div key={index} style={{ position: "relative" }}>
										<img
											src={image.url}
											alt={`Author Image ${index}`}
											style={{
												height: "95px",
												width: "95px",
												marginRight: "10px",
											}}
										/>
										<button
											className="remove-image-button"
											onClick={() => removeAuthorImage(index)}
											style={{
												position: "absolute",
												top: "0px",
												right: "5px",
												background: "none",
												border: "none",
												color: "#507acb",
												cursor: "pointer",
												fontWeight: "700",
											}}
										>
											&#x2715;
										</button>
									</div>
								))}
						</div>
						<MediaPlaceholder
							labels={{
								title: "Author Images",
							}}
							multiple={true}
							value={attributes.authorImages}
							onSelect={changeAuthorImages}
							isAppender={true}
						></MediaPlaceholder>
						<h3>DOI Number</h3>
						<TextControl
							label={"Enter the DOI number"}
							value={attributes.doiNumber}
							onChange={changeDOINumber}
						></TextControl>
						<h3>Abstract Link</h3>
						<TextControl
							label={"Enter the Abstract Link"}
							value={attributes.abstractLink}
							onChange={changeAbstractLink}
						></TextControl>
						<h3>Download Link</h3>
						<TextControl
							label={"Enter the Downloadable Link"}
							value={attributes.downloadLink}
							onChange={changeDownloadLink}
						></TextControl>
						<h3>Publish Date</h3>
						<DatePicker
							currentDate={
								attributes.publishedDate ? attributes.publishedDate : new Date()
							}
							onChange={(newDate) => setAttributes({ publishedDate: newDate })}
							is12Hour={true}
						/>
					</PanelBody>
				</>,
			)}
		</div>
	);
}
