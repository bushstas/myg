import * as React from 'react';
import css from './style.scss';

export class Hit extends React.PureComponent {
  static displayName = 'Hit';

  state = {};

  render() {
    const { weaponRight, weaponLeft } = this.props;

    let styleLeft;
    const styleRight = {
      backgroundImage: `url(/assets/images/hit/${weaponRight}/r.png)`,
      transform: `rotate(180deg)`,
      marginTop: 20,
    };
    if (weaponLeft) {
      styleLeft = {
        backgroundImage: `url(/assets/images/hit/${weaponLeft}/l.png)`,
      };
    }

    return (
          <React.Fragment>
        <div className={css.main} style={styleRight} />
        {weaponLeft && <div className={css.main} style={styleLeft} />}
      </React.Fragment>
    );
  }
}
