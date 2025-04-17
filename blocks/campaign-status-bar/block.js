import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
const { useSelect } = wp.data;


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
        const { attributes, ...rest } = props;
        const blockProps = useBlockProps();

        // if context.postId is 0 or empty, set attributes.__editorPostId to 0
        attributes.__editorPostId = rest?.context?.postId ?? 0;
        

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