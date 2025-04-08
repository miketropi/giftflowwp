import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';


registerBlockType('giftflowwp/campaign-status-bar', {
    apiVersion: 3,
    title: 'Campaign Status Bar',
    icon: 'block-default',
    category: 'giftflowwp',
    attributes: {
        __editorPostId: {
            type: 'number',
            default: 0,
        },
    },
    usesContext: ['postId'],
    edit: (props) => {
        const { attributes, context } = props;
        const blockProps = useBlockProps();

        attributes.__editorPostId = parseInt(context.postId);

        return (
            <div {...blockProps}>
                <Disabled>
                    <ServerSideRender 
                        block="giftflowwp/campaign-status-bar" 
                        attributes={ attributes } />
                </Disabled>
            </div>
        );
    },
});