import { registerBlockType } from '@wordpress/blocks';

registerBlockType('themidev/portfolio-grid', {
  edit() {
    return <p>Portfolio Grid (Editor Preview)</p>;
  },
  save() {
    return null; // dynamic render later
  }
});
