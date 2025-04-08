import { registerBlockType } from '@wordpress/blocks';
import { RichText } from '@wordpress/block-editor';

registerBlockType('giftflowwp/campaign-status-bar', {
    title: 'Campaign Status Bar',
    icon: 'block-default',
    category: 'giftflowwp',
    attributes: {
        content: { type: 'string', source: 'html', selector: 'p' },
    },
    edit({ attributes, setAttributes }) {
        return (
            <RichText
                tagName="p"
                value={attributes.content}
                onChange={(content) => setAttributes({ content })}
                placeholder="Enter text..."
            />
        );
    },
    save({ attributes }) {
        return <RichText.Content tagName="p" value={attributes.content} />;
    },
});