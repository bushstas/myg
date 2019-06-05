import { BattleService } from 'services';
import { CommonActions } from './common';

const service = new BattleService();

export class BattleActions extends CommonActions {
  name = 'battle';

  load = async () => {
    this.data = await service.get();
    this.addToStore(this.data);
  };

  endTurn = async () => {
    this.currentAction = 0;
    this.actions = await service.endTurn();
    this.doAction();
  };

  doAction = () => {
    if (this.actions[this.currentAction]) {
      const { characters } = this.data;
      const { id, props, duration = 0 } = this.actions[this.currentAction];

      this.data = {
        ...this.data,
        characters: {
          ...characters,
          [id]: {
            ...characters[id],
            ...props,
          },
        },
      };

      this.addToStore(this.data);
      this.currentAction++;
      if (duration) {
        setTimeout(this.doAction, duration * 100);
      } else {
        this.doAction();
      }
    }
  };
}
