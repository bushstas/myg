import * as React from 'react';
import { Character } from '../Character';
import css from './style.scss';

export class BattleField extends React.PureComponent {
  static displayName = 'BattleField';

  constructor(props) {
    super(props);
    const { size } = props;
    this.x = 0;
    this.y = 0;
    this.width = (size[0] + 4) * 100;
    this.height = (size[1] + 4) * 100;
    this.style = {
      width: this.width,
      height: this.height,
    };
  }

  componentDidMount() {
    const { characters } = this.props;
    const images = [];
    Object.keys(characters).forEach(id => {
      if (images.indexOf(characters[id].img) === -1) {
        const img = new Image();
        img.src = `/assets/images/creatures/${characters[id].type}/${
          characters[id].img
        }_d.png`;
        if (characters[id].leader) {
          img.src = `/assets/images/creatures/${characters[id].type}/${
            characters[id].img
          }_rh.png`;
        }
      }
    });
  }

  handleMouseDown = e => {
    document.body.addEventListener('mousemove', this.handleMouseMove, false);
    document.body.addEventListener('mouseup', this.handleMouseUp, false);
    document.body.addEventListener('mouseleave', this.handleMouseUp, false);
    this.pageX = e.pageX;
    this.pageY = e.pageY;
    const mapSize = this.refs.outer.getBoundingClientRect();
    this.limitX = -(this.width - mapSize.width);
    this.limitY = -(this.height - mapSize.height);
  };

  handleDragStart = e => {
    e.preventDefault();
  };

  handleMouseMove = e => {
    this.x = this.x + e.pageX - this.pageX;
    this.y = this.y + e.pageY - this.pageY;

    if (this.x > 0) {
      this.x = 0;
    } else if (this.x < this.limitX) {
      this.x = this.limitX;
    }

    if (this.y > 0) {
      this.y = 0;
    } else if (this.y < this.limitY) {
      this.y = this.limitY;
    }

    this.refs.map.style.marginLeft = `${this.x}px`;
    this.refs.map.style.marginTop = `${this.y}px`;
    this.pageX = e.pageX;
    this.pageY = e.pageY;
  };

  handleMouseUp = e => {
    document.body.removeEventListener('mousemove', this.handleMouseMove, false);
    document.body.removeEventListener('mouseup', this.handleMouseUp, false);
    document.body.removeEventListener('mouseleave', this.handleMouseUp, false);
  };

  renderCharacters() {
    const { characters } = this.props;
    return Object.keys(characters).map(id => (
      <Character id={id} {...characters[id]} />
    ));
  }

  render() {
    const { dictionary, size, onEndTurn } = this.props;
    return (
      <div className={css.main}>
        <div ref="outer" className={css.map}>
                <div
            ref="map"
            className={css.inner}
            style={this.style}
            onMouseDown={this.handleMouseDown}
            onDragStart={this.handleDragStart}
                    <div className={css.boundary} />
            <div className={css.field}>
              <React.Fragment>{this.renderCharacters()}</React.Fragment>
            </div>
          </div>
        </div>
        <div className={css.endturn} onClick={onEndTurn}>
                {dictionary.endturn}
        </div>
      </div>
    );
  }
}
