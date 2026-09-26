/**
 * PodNest Visual Regressor - Block Editor Entry Point
 *
 * Registers the theme's blocks in the editor with a placeholder preview.
 * They are server rendered, so the front end markup comes from PNVR_Blocks.
 *
 * @package PodNest Visual Regressor
 * @author Kevin Pirnie <me@kpirnie.com>
 * @version 1.0.1
 */

const { registerBlockType } = window.wp.blocks;
const { useBlockProps } = window.wp.blockEditor;
const { __ } = window.wp.i18n;
const el = window.wp.element.createElement;

/**
 * The blocks: name, title, dashicon, and where their content is managed
 */
const blocks = [
    ['pnvr/marquee', __('Marquee', 'pn-vr'), 'leftright', __('The scrolling strip. Items come from VR Content > Marquee.', 'pn-vr')],
    ['pnvr/features', __('Features Grid', 'pn-vr'), 'grid-view', __('The feature cards. Cards come from VR Content > Features.', 'pn-vr')],
    ['pnvr/how-it-works', __('How It Works Steps', 'pn-vr'), 'editor-ol', __('The pipeline steps. Steps come from VR Content > How It Works.', 'pn-vr')],
    ['pnvr/security', __('Security Grid', 'pn-vr'), 'shield', __('The security cards. Cards come from VR Content > Security.', 'pn-vr')],
    ['pnvr/pricing', __('Pricing', 'pn-vr'), 'money-alt', __('The tier cards and the Trial vs Paid table. Tiers come from VR Content > Pricing.', 'pn-vr')],
    ['pnvr/ai-judge-points', __('AI Judge Points', 'pn-vr'), 'yes-alt', __('What the AI review adds, and the pass / warn / fail legend.', 'pn-vr')],
    ['pnvr/ai-review', __('AI Review Illustration', 'pn-vr'), 'format-image', __('The illustrated AI review panel.', 'pn-vr')]
];

/**
 * Build the editor placeholder for a block
 */
const preview = (blockProps, icon, title, description) => el(
    'div',
    Object.assign({}, blockProps, {
        style: {
            padding: '20px 24px',
            fontFamily: '"JetBrains Mono", monospace',
            color: '#dde8f5',
            background: '#0c1530',
            border: '1px solid #1e2d52',
            borderRadius: '10px'
        }
    }),
    el(
        'div',
        { style: { display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '8px' } },
        el('span', { className: `dashicons dashicons-${icon}`, style: { color: '#2bff88' } }),
        el('strong', { style: { fontSize: '0.78rem', letterSpacing: '0.12em', textTransform: 'uppercase', color: '#2bff88' } }, title)
    ),
    el('p', { style: { margin: 0, fontSize: '0.8rem', lineHeight: 1.5, color: '#6b8cae' } }, description)
);

// register them
blocks.forEach(([name, title, icon, description]) => {
    registerBlockType(name, {
        apiVersion: 3,
        title,
        icon,
        description,
        category: 'pnvr',
        supports: { html: false },
        edit: () => preview(useBlockProps(), icon, title, description),
        save: () => null
    });
});