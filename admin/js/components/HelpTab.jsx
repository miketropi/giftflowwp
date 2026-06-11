import React from 'react';
import { BookOpen, Flag, Layout, Code, MessageCircle, ExternalLink } from 'lucide-react';

const HelpTab = () => {
  const admin = typeof giftflow_admin !== 'undefined' ? giftflow_admin : {};
  const docs_url = admin.docs_url || 'https://giftflow-doc.beplus-agency.cloud/';
  const support_url = admin.support_url || 'https://giftflow.beplus-agency.cloud/contact';

  const docs = [
    {
      title: 'Introduction: platform overview & quick start',
      description: 'Learn how GiftFlow fits together — campaigns, donations, and donor records — and what you need to go live. Includes payment options, reCAPTCHA setup, and a step-by-step quick start.',
      url: docs_url,
      tag: 'Essentials',
      icon: BookOpen,
    },
    {
      title: 'Creating your first campaign (walkthrough)',
      description: 'From a blank campaign to a published page: set goals, add images and galleries, configure preset amounts, choose payment methods, and embed the donation experience.',
      url: docs_url + 'usage/guide-first-campaign',
      tag: 'Tutorial',
      icon: Flag,
    },
    {
      title: 'Blocks, shortcodes & block templates',
      description: 'Add donation buttons, campaign content, progress bars, galleries, donor account areas, and sharing. Reference covers attributes and template overrides.',
      url: docs_url + 'blocks-and-shortcodes',
      tag: 'Frontend',
      icon: Layout,
    },
    {
      title: 'Payment hooks, filters & gateway behavior',
      description: 'Register custom gateways, react to payment success or failure, adjust form data, and work with webhooks. Covers one-time and recurring flow patterns.',
      url: docs_url + 'hooks-and-filters/payment-hooks-filters',
      tag: 'Developer',
      icon: Code,
    },
  ];

  const devLinks = [
    { label: 'Helper functions', url: docs_url + 'helper-functions-reference' },
    { label: 'Template overrides', url: docs_url + 'hooks-and-filters/developer-guide-template-override' },
    { label: 'Metabox & settings', url: docs_url + 'hooks-and-filters/developer-guide-metabox-settings' },
  ];

  return (
    <div className="giftflow-dashboard-helps">

      {/* Jump links */}
      <nav className="giftflow-dashboard-helps__jump" aria-label="Quick links to documentation">
        <span className="giftflow-dashboard-helps__jump-label">Jump to</span>
        <ul className="giftflow-dashboard-helps__jump-list">
          {docs.map((doc, i) => (
            <li key={i}>
              <a className="giftflow-dashboard-helps__jump-link" href={doc.url} target="_blank" rel="noopener noreferrer">
                {doc.title}
              </a>
            </li>
          ))}
        </ul>
      </nav>

      <div className="giftflow-dashboard-helps__body">
        <div className="giftflow-dashboard-helps__primary">

          {/* Featured */}
          <a className="giftflow-dashboard-helps__featured" href={docs_url} target="_blank" rel="noopener noreferrer">
            <span className="giftflow-dashboard-helps__featured-icon" aria-hidden="true">
              <BookOpen size={20} strokeWidth={1.5} />
            </span>
            <span className="giftflow-dashboard-helps__featured-text">
              <span className="giftflow-dashboard-helps__featured-title">Browse all guides &amp; API reference</span>
              <span className="giftflow-dashboard-helps__featured-desc">
                Usage, sandbox setup, blocks, hooks, examples, and helper functions — searchable on the docs site.
              </span>
            </span>
            <span className="giftflow-dashboard-helps__featured-arrow" aria-hidden="true">
              <ExternalLink size={14} strokeWidth={2} />
            </span>
          </a>

          {/* Doc tiles */}
          <div className="giftflow-dashboard-helps__grid" role="list">
            {docs.map((doc, i) => (
              <a key={i} className="giftflow-dashboard-helps__tile" href={doc.url} target="_blank" rel="noopener noreferrer" role="listitem">
                {doc.tag && <span className="giftflow-dashboard-helps__tile-tag">{doc.tag}</span>}
                <span className="giftflow-dashboard-helps__tile-icon" aria-hidden="true">
                  <doc.icon size={18} strokeWidth={1.5} />
                </span>
                <span className="giftflow-dashboard-helps__tile-body">
                  <span className="giftflow-dashboard-helps__tile-title">{doc.title}</span>
                  {doc.description && <span className="giftflow-dashboard-helps__tile-desc">{doc.description}</span>}
                </span>
              </a>
            ))}
          </div>
        </div>

        {/* Rail */}
        <aside className="giftflow-dashboard-helps__rail" aria-labelledby="giftflow-helps-dev-heading">
          <div className="giftflow-dashboard-helps__rail-card">
            <h3 id="giftflow-helps-dev-heading" className="giftflow-dashboard-helps__rail-title">
              <span className="giftflow-dashboard-helps__rail-title-icon" aria-hidden="true"><Code size={16} strokeWidth={2} /></span>
              Developers
            </h3>
            <p className="giftflow-dashboard-helps__rail-text">Hooks, theme overrides, and admin extensions.</p>
            <ul className="giftflow-dashboard-helps__rail-links">
              {devLinks.map((link, i) => (
                <li key={i}>
                  <a href={link.url} target="_blank" rel="noopener noreferrer">{link.label}</a>
                </li>
              ))}
            </ul>
          </div>

          <div className="giftflow-dashboard-helps__rail-card">
            <h3 className="giftflow-dashboard-helps__rail-title">
              <span className="giftflow-dashboard-helps__rail-title-icon" aria-hidden="true"><MessageCircle size={16} strokeWidth={2} /></span>
              Need help?
            </h3>
            <p className="giftflow-dashboard-helps__rail-text">Setup, bugs, or custom work — talk to our team.</p>
            <a className="button button-primary giftflow-dashboard-helps__rail-btn" href={support_url} target="_blank" rel="noopener noreferrer">
              Contact support
            </a>
          </div>
        </aside>
      </div>
    </div>
  );
};

export default HelpTab;
