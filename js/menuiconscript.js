jQuery(document).ready(function ($) {
	let selectIconBtn = document.querySelectorAll('.menu-icon-start-btn');
	let modal = document.querySelector('.menu-icons-modal-wrapper');
	let close = document.querySelector('.close-modal-btn');

	let allIconWrapper = document.querySelector('.all-menu-icons-wrapper');
	let allIconCont = document.querySelectorAll('.all-menu-icons-container');
	let FACont = document.querySelector('.font-awesome-container');
	let MATCont = document.querySelector('.material-icon-container');
	let DASHCont = document.querySelector('.dashicon-container');
	let SEARCHCont = document.querySelector('.search-result-icon-container');
	let filterTabBtns = document.querySelectorAll('#filter-tab-btn');

	let searchInput = document.querySelector('#search-bar-input-field');

	let ITEM_ID = null;
	let ITEM_ICON_NAME = null;

	// SET selected icon library default to Font Awesome
	let SELECTED_ICON_LIBRARY = 'ALL-ICONS';

	// Map all Font Awesome Icons
	let allfontawesomeIcons = allFAicons
		.map((icon_class) => {
			let trimmedIconname = icon_class.replace(/\s/g, '');
			return `<div class="select-icon-container icon-container-${trimmedIconname}" 
					data-iconname="${icon_class}"> 
					<i class='font-awesome-icon ${icon_class}'></i>
				</div>`;
		})
		.join('');
	FACont.innerHTML = `${allfontawesomeIcons}`;

	// Map all Material Icons and Load the Fonts
	let allmaterialIcons = allMaticons
		.map((icon_name) => {
			let trimmedIconname = icon_name.replace(/\s/g, '');
			return `<div class="select-icon-container icon-container-${trimmedIconname}"
					data-iconname="material ${icon_name}"
					> 
				<i class='material-icons'>${icon_name}</i>
			</div>`;
		})
		.join('');
	MATCont.innerHTML = `${allmaterialIcons}`;

	// Map all DashIcons and Load the Fonts
	let allDashIcons = allDash
		.map((icon_class) => {
			let trimmedIconname = icon_class.replace(/\s/g, '');
			return `<div class="select-icon-container icon-container-${trimmedIconname}"
					data-iconname="${icon_class}"
					> 
			<span class="dashicons ${icon_class}"></span>
		</div>`;
		})
		.join('');
	DASHCont.innerHTML = `${allDashIcons}`;

	// Display Modal On Click of Select Icon Button and Save the ITEM_ID of the Nav Item
	selectIconBtn.forEach((btn) => {
		btn.addEventListener('click', (e) => {
			e.preventDefault();

			ITEM_ID = btn.getAttribute('data-itemid');
			modal.classList.add('is-active');

			// For the Nav Menu Items that already have a Icon Selected. Display their Selected Icon in Modal
			let menuItemMetaIconName = document.querySelector(
				`#_menu_icon_icon_name-${ITEM_ID}`
			).value;
			let menuItemMetaIconLib = document.querySelector(
				`#_menu_icon_icon_library-${ITEM_ID}`
			).value;

			if (menuItemMetaIconName && menuItemMetaIconLib) {
				let trimmedMetaIcon = menuItemMetaIconName.replace(/\s/g, '');
				let selectIconContainer = document.querySelector(
					`.icon-container-${trimmedMetaIcon}`
				);
				//console.log(trimmedMetaIcon);
				selectIconContainer.classList.add('selected');
			}
		});
	});

	// On Selection of the Item, Get the classname of Icon and add selected class and update the hidden input values
	let iconCont = document.querySelectorAll('.select-icon-container');
	iconCont.forEach((icon) => {
		icon.addEventListener('click', () => {
			unselectAllIcons();
			let iconName;
			let iconClassname;
			let iconLibrary;
			let iconpreview = document.querySelector(`.icon-preview-${ITEM_ID}`);

			let hiddenIconNameField = document.querySelector(
				`#_menu_icon_icon_name-${ITEM_ID}`
			);
			let hiddenIconLibraryField = document.querySelector(
				`#_menu_icon_icon_library-${ITEM_ID}`
			);

			let childClass = icon.childNodes[1].getAttribute('class');
			let childClassArray = childClass.split(' ');
			iconLibrary = childClassArray[0];

			switch (iconLibrary) {
				case 'font-awesome-icon':
					// get classname of selected icon
					iconClassname = icon
						.querySelector('.font-awesome-icon')
						.getAttribute('class');
					icon.classList.add('selected');
					iconName = iconClassname.substr(18);
					ITEM_ICON_NAME = iconName;
					// change the preview icon
					iconpreview.innerHTML = `<i class="${ITEM_ICON_NAME}"></i> `;
					// update value of hidden field to the new classname
					hiddenIconLibraryField.value = 'font-awesome-icon';
					hiddenIconNameField.value = `${ITEM_ICON_NAME}`;
					break;

				case 'material-icons':
					iconName = icon.querySelector('.material-icons').innerText;
					icon.classList.add('selected');
					ITEM_ICON_NAME = iconName;
					// change the preview icon
					iconpreview.innerHTML = `<i class="material-icons">${ITEM_ICON_NAME}</i> `;
					// update value of hidden field to the new classname
					hiddenIconLibraryField.value = 'material-icons';
					hiddenIconNameField.value = `${ITEM_ICON_NAME}`;
					break;

				case 'dashicons':
					iconClassname = icon
						.querySelector('.dashicons')
						.getAttribute('class');
					icon.classList.add('selected');
					iconName = iconClassname.substr(10);
					ITEM_ICON_NAME = iconName;
					// change the preview icon
					iconpreview.innerHTML = `<i class="dashicons ${ITEM_ICON_NAME}"></i> `;
					// update value of hidden field to the new classname
					hiddenIconLibraryField.value = 'dashicons';
					hiddenIconNameField.value = `${ITEM_ICON_NAME}`;
					break;

				default:
					break;
			}

			//console.log(iconName);
		});
	});

	function unselectAllIcons() {
		iconCont.forEach((item) => {
			item.classList.remove('selected');
		});
	}

	// filter Icon library
	filterTabBtns.forEach((tab) => {
		tab.addEventListener('click', () => {
			let library = tab.getAttribute('data-library');
			FilterIcons(library, tab);
		});
	});

	function FilterIcons(library, tab) {
		allIconCont.forEach((cont) => (cont.style.display = 'none'));
		filterTabBtns.forEach((btn) => btn.classList.remove('is-active'));
		allIconWrapper.scrollTop = 0;

		switch (library) {
			case 'font-awesome':
				SELECTED_ICON_LIBRARY = 'FONT-AWESOME';
				FACont.style.display = 'grid';
				tab.classList.add('is-active');
				break;
			case 'material-icon':
				SELECTED_ICON_LIBRARY = 'MATERIAL-ICON';
				MATCont.style.display = 'grid';
				tab.classList.add('is-active');
				break;
			case 'dash-icon':
				SELECTED_ICON_LIBRARY = 'DASH-ICON';
				DASHCont.style.display = 'grid';
				tab.classList.add('is-active');
				break;
			case 'all-icons':
				SELECTED_ICON_LIBRARY = 'ALL-ICON';
				FACont.style.display = 'grid';
				MATCont.style.display = 'grid';
				DASHCont.style.display = 'grid';
				tab.classList.add('is-active');
			case 'searched':
				SELECTED_ICON_LIBRARY = 'ALL-ICON';
				SEARCHCont.style.display = 'grid';
				tab.classList.add('is-active');
			default:
				break;
		}
	}

	// Close the Modal
	close.addEventListener('click', () => {
		unselectAllIcons();
		modal.classList.remove('is-active');
		searchInput.value = '';

		let tab = document.querySelector(`[data-library=all-icons]`);
		setTimeout(() => {
			FilterIcons('all-icons', tab);
		}, 500);
	});

	// Search Bar Input Field
	let allIcons = document.querySelectorAll('.select-icon-container');

	// When Enter is Pressed get the searched input and filter Icons
	searchInput.addEventListener('keyup', (e) => {
		if (e.keyCode === 13) {
			let searchTerm = searchInput.value;
			let searchTermLowerCase = searchTerm.toLowerCase();

			// Filter the icon containers according to the data tag
			let searchRESULT = [...allIcons].filter((icon) => {
				let thisiconname = icon.getAttribute('data-iconname');
				let result = thisiconname.search(`${searchTermLowerCase}`);
				if (result > 0) {
					return thisiconname;
				}
			});
			// Clear the Search Container
			SEARCHCont.innerHTML = '';

			// Map the searched Result and Fill into the Search Container
			if (searchRESULT.length > 0) {
				searchRESULT.forEach((result_icon) => {
					SEARCHCont.appendChild(result_icon);
				});
			} else {
				SEARCHCont.innerHTML =
					'<div class="menu-icon-no-results">No Results</div>';
			}

			// Select All Icons Tab on search
			let tab = document.querySelector(`[data-library=all-icons]`);
			FilterIcons('searched', tab);
		}
	});
});
