import * as React from 'react';
import css from './style.scss';

export class Damage extends React.PureComponent {
    static displayName = 'Damage';
    state = {
        opacity: 0
    };

    componentDidMount() {
        setTimeout(() => {
            this.setState({opacity: 1});
            setTimeout(() => {
                this.setState({opacity: 0});
                setTimeout(() => {
                    this.props.onDamageShown();
                }, 400);
            }, 450);
        }, 50);
    }

    render() {
        const {value, type} = this.props;
        const {opacity} = this.state;



        const bloodStyle = {
            backgroundImage: `url(/assets/images/damage/${type}.png)`,
            opacity
        };

        return (
            <React.Fragment>
                <div
                    className={css.blood}
                    style={bloodStyle}
                />
            </React.Fragment>
        );
    }
}
