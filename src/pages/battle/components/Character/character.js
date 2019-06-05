import * as React from 'react';
import { Hit } from '../Hit';
import { Damage } from '../Damage';
import css from './style.scss';


export class Character extends React.PureComponent {
    static displayName = 'Character';
    state = {};

    componentDidUpdate(prevProps) {
        const {
            sayKey,
            say,
            sayDuration = 20,
            hitKey,
            hit,
            dmgKey,
            dmg
        } = this.props;
        if (sayKey && sayKey !== prevProps.sayKey) {
            this.setState({say});
            setTimeout(() => {
                this.setState({say: null});
            }, sayDuration * 100);
        }
        if (hitKey && hitKey !== prevProps.hitKey) {
            this.setState({hit, hitting: true});
            setTimeout(() => {
                this.setState({hitting: false});
            }, 300);
            setTimeout(() => {
                this.setState({hit: null});
            }, 600);
        }
        if (dmgKey && dmgKey !== prevProps.dmgKey) {
            this.setState({dmg});
        }
    }

    handleHitEnd = () => {
        this.setState({hit: null});
    }

    handleDamageShown = () => {
        this.setState({dmg: null});   
    }


    render() {
        const {say, hit, hitting, dmg} = this.state;
        const {
            type,
            cid,
            dir,
            x,
            y,
            speed,
            wr,
            wl,
            dmgtype,
            height = 100
        } = this.props;

        const dur = (hit || hitting ? 3 : 15 - speed) * .1;
        const style = {
            backgroundImage: `url(/assets/images/creatures/${type}/${cid}_${dir}.png)`,
            left: (x - 1) * 100,
            top: y * 100 + (hitting ? 25 : 0),
            marginTop: -40 - height,
            transition: `left ${dur}s ease-in-out, top ${dur}s ease-in-out`,
            height
        };

        return (
            <div
                className={css.main}
                style={style}
            >
                {say && 
                    <div className={css.say}>
                        <div className={css.bg} />
                        <div className={css.text}>
                            {say}
                        </div>
                    </div>
                }
                {hit && 
                    <Hit
                        hit={hit}
                        weaponRight={wr}
                        weaponLeft={wl}
                    />
                }
                {dmg && 
                    <Damage
                        value={dmg}
                        type={dmgtype}
                        onDamageShown={this.handleDamageShown}
                    />
                }
            </div>
        );
    }
}
