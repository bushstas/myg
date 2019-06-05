import { CommonService } from './common';

export class BattleService extends CommonService {
    name = 'battle';

    get = () => {
        return this.fetch('get');
    }

    endTurn = () => {
        return this.fetch('end-turn');
    }
}