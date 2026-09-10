<?php

namespace App\Enums\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Estado de cobranza de un pedido: distingue lo que el cliente ya pago
 * (suma de sus pagos) de lo que solo prometio pagar en una fecha.
 */
enum PaymentPromiseStatusEnum: string implements HasLabel, HasColor, HasIcon
{
    /** Ya no debe nada. */
    case liquidado = 'liquidado';

    /** Debe saldo y se comprometio a una fecha que aun no llega. */
    case comprometido = 'comprometido';

    /** Debe saldo y la fecha comprometida ya paso. */
    case vencido = 'vencido';

    /** Debe saldo y nadie capturo cuando lo va a pagar. */
    case sin_compromiso = 'sin_compromiso';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::liquidado      => 'Liquidado',
            self::comprometido   => 'Por cobrar',
            self::vencido        => 'Vencido',
            self::sin_compromiso => 'Sin compromiso',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::liquidado      => 'success',
            self::comprometido   => 'info',
            self::vencido        => 'danger',
            self::sin_compromiso => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::liquidado      => 'heroicon-m-check-circle',
            self::comprometido   => 'heroicon-m-calendar-days',
            self::vencido        => 'heroicon-m-exclamation-triangle',
            self::sin_compromiso => 'heroicon-m-question-mark-circle',
        };
    }
}
