interface ConfirmEventDetail {
  onConfirm?: () => void;
  title?: string;
  message?: string;
  action?: string;
}

export class ConfirmEvent extends CustomEvent<ConfirmEventDetail> {
  constructor(detail: ConfirmEventDetail) {
    super("confirm", { detail, bubbles: true });
  }
}
