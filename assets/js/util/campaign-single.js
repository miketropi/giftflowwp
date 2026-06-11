/**
 * GiftFlow Tab Widget Class
 *
 * A reusable class for managing tabbed content widgets.
 * Supports both legacy selectors (.giftflow-tab-widget-tab-item)
 * and BEM selectors (.giftflow-tab-widget__tab).
 *
 * @package GiftFlow
 * @since 1.0.0
 */

class GiftFlowTabWidget {
	static defaults = {
		tabItemSelector: '.giftflow-tab-widget__tab,.giftflow-tab-widget-tab-item',
		contentItemSelector: '.giftflow-tab-widget__panel,.giftflow-tab-widget-content-item',
		contentContainerSelector: '.giftflow-tab-widget__content',
		activeClass: 'is-active',
		tabIdAttribute: 'tabId',
		useHash: true,
		hashKeywords: {
			comment: 'comments',
		},
	};

	constructor(selector, options = {}) {
		this.container = typeof selector === 'string' ? document.querySelector(selector) : selector;

		if (!this.container) {
			return;
		}

		this.options = { ...GiftFlowTabWidget.defaults, ...options };
		this.activeTabId = null;

		this.cacheElements();
		this.bindEvents();
		this.initFromHash();
	}

	cacheElements() {
		const contentSelectors = this.options.contentContainerSelector.split(',').map(s => s.trim());
		for (const sel of contentSelectors) {
			const el = this.container.querySelector(sel);
			if (el) {
				this.contentContainer = el;
				break;
			}
		}
		if (!this.contentContainer) {
			this.contentContainer = this.container;
		}

		const tabSelectors = this.options.tabItemSelector.split(',').map(s => s.trim());
		let tabs = [];
		for (const sel of tabSelectors) {
			const found = this.container.querySelectorAll(sel);
			if (found.length) {
				tabs = found;
				break;
			}
		}
		this.tabItems = tabs;

		const panelSelectors = this.options.contentItemSelector.split(',').map(s => s.trim());
		let panels = [];
		for (const sel of panelSelectors) {
			const found = this.contentContainer.querySelectorAll(sel);
			if (found.length) {
				panels = found;
				break;
			}
		}
		this.contentItems = panels;
	}

	bindEvents() {
		this.tabItems.forEach((tabItem) => {
			tabItem.addEventListener('click', (e) => {
				e.preventDefault();
				const tabId = tabItem.dataset[this.options.tabIdAttribute];
				this.activateTab(tabId);
			});

			tabItem.addEventListener('keydown', (e) => {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					const tabId = tabItem.dataset[this.options.tabIdAttribute];
					this.activateTab(tabId);
				}

				if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
					e.preventDefault();
					this.navigateWithArrows(e.key === 'ArrowRight' ? 1 : -1, tabItem);
				}
			});
		});

		if (this.options.useHash) {
			window.addEventListener('hashchange', () => this.initFromHash());
		}
	}

	initFromHash() {
		if (!this.options.useHash) return;

		const hash = window.location.hash.substring(1);
		if (hash) {
			for (const [keyword, tabId] of Object.entries(this.options.hashKeywords)) {
				if (hash.includes(keyword)) {
					this.activateTab(tabId);
					return;
				}
			}
			this.activateTab(hash);
		}
	}

	navigateWithArrows(direction, currentTab) {
		const tabsArray = Array.from(this.tabItems);
		const currentIndex = tabsArray.indexOf(currentTab);
		let newIndex = currentIndex + direction;
		if (newIndex < 0) newIndex = tabsArray.length - 1;
		else if (newIndex >= tabsArray.length) newIndex = 0;

		const newTab = tabsArray[newIndex];
		newTab.focus();
		this.activateTab(newTab.dataset[this.options.tabIdAttribute]);
	}

	activateTab(tabId) {
		if (!tabId || tabId === this.activeTabId) return this;

		const targetTab = this.container.querySelector(
			`[data-${this.toKebabCase(this.options.tabIdAttribute)}="${tabId}"]`
		);
		const targetContent = this.contentContainer.querySelector(
			`[data-tab-panel="${tabId}"], [data-${this.toKebabCase(this.options.tabIdAttribute)}="${tabId}"]`
		);

		if (!targetTab || !targetContent) return this;

		this.tabItems.forEach((tab) => {
			tab.classList.remove(this.options.activeClass);
			tab.setAttribute('aria-selected', 'false');
			tab.setAttribute('tabindex', '-1');
		});

		this.contentItems.forEach((content) => {
			content.classList.remove(this.options.activeClass);
			content.setAttribute('hidden', '');
			content.setAttribute('aria-hidden', 'true');
		});

		targetTab.classList.add(this.options.activeClass);
		targetTab.setAttribute('aria-selected', 'true');
		targetTab.setAttribute('tabindex', '0');

		targetContent.classList.add(this.options.activeClass);
		targetContent.removeAttribute('hidden');
		targetContent.setAttribute('aria-hidden', 'false');

		this.activeTabId = tabId;

		this.container.dispatchEvent(
			new CustomEvent('giftflow:tab:changed', {
				detail: { tabId, tab: targetTab, content: targetContent, instance: this },
				bubbles: true,
			})
		);

		return this;
	}

	toKebabCase(str) {
		return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
	}

	getActiveTabId() { return this.activeTabId; }

	getActiveTab() {
		if (!this.activeTabId) return null;
		return this.container.querySelector(
			`[data-${this.toKebabCase(this.options.tabIdAttribute)}="${this.activeTabId}"]`
		);
	}

	getActiveContent() {
		if (!this.activeTabId) return null;
		return this.contentContainer.querySelector(
			`[data-tab-panel="${this.activeTabId}"], [data-${this.toKebabCase(this.options.tabIdAttribute)}="${this.activeTabId}"]`
		);
	}

	next() {
		const tabsArray = Array.from(this.tabItems);
		const currentIndex = tabsArray.findIndex((tab) => tab.dataset[this.options.tabIdAttribute] === this.activeTabId);
		const nextIndex = (currentIndex + 1) % tabsArray.length;
		return this.activateTab(tabsArray[nextIndex].dataset[this.options.tabIdAttribute]);
	}

	prev() {
		const tabsArray = Array.from(this.tabItems);
		const currentIndex = tabsArray.findIndex((tab) => tab.dataset[this.options.tabIdAttribute] === this.activeTabId);
		const prevIndex = (currentIndex - 1 + tabsArray.length) % tabsArray.length;
		return this.activateTab(tabsArray[prevIndex].dataset[this.options.tabIdAttribute]);
	}

	refresh() {
		this.cacheElements();
		return this;
	}

	destroy() {
		this.tabItems.forEach((tabItem) => {
			tabItem.replaceWith(tabItem.cloneNode(true));
		});
		if (this.options.useHash) {
			window.removeEventListener('hashchange', this.initFromHash);
		}
		this.container = null;
		this.contentContainer = null;
		this.tabItems = null;
		this.contentItems = null;
	}

	static initAll(selector, options = {}) {
		const instances = [];
		document.querySelectorAll(selector).forEach((element) => {
			instances.push(new GiftFlowTabWidget(element, options));
		});
		return instances;
	}
}

document.addEventListener('DOMContentLoaded', () => {
	window.giftflowTabWidgets = GiftFlowTabWidget.initAll('.giftflow-tab-widget');
});

if (typeof module !== 'undefined' && module.exports) {
	module.exports = GiftFlowTabWidget;
}

window.GiftFlowTabWidget = GiftFlowTabWidget;
