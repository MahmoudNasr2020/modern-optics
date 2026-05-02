@extends('dashboard.layouts.master')

@section('title', 'Stock Movement History')

@section('content')

    <style>
        .timeline-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            animation: fadeInUp 0.5s ease-out;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }

        .timeline-item {
            position: relative;
            padding: 20px 0 20px 100px;
            animation: fadeInUp 0.5s ease-out;
        }

        .timeline-icon {
            position: absolute;
            left: 33px;
            top: 25px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .timeline-icon.in { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .timeline-icon.out { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .timeline-icon.transfer-in { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .timeline-icon.transfer-out { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
        .timeline-icon.sale { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);}
        .timeline-icon.adjustment { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .timeline-icon.reserve { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }

        .timeline-content {
            background: white;
            border: 2px solid #f0f2f5;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .timeline-content:hover {
            border-color: #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
            transform: translateX(5px);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .timeline-type {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .timeline-type.in { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .timeline-type.out { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .timeline-type.transfer-in { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .timeline-type.transfer-out { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; }
        .timeline-type.adjustment { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .timeline-type.return {background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);color: white;}
        .timeline-type.reserve {background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%);color: white;}
        .timeline-type.sale {background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);color: white;}



        .timeline-date {
            color: #999;
            font-size: 13px;
        }

        .timeline-quantity {
            font-size: 24px;
            font-weight: 900;
            margin: 10px 0;
        }

        .timeline-quantity.positive { color: #27ae60; }
        .timeline-quantity.negative { color: #e74c3c; }

        .timeline-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f2f5;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .balance-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
        }

        .balance-before { background: #f8f9fa; color: #666; }
        .balance-after { background: #667eea; color: white; }

        .stock-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .stock-summary::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .stock-summary h3 {
            margin: 0 0 20px 0;
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .summary-item {
            text-align: center;
        }

        .summary-value {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 13px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .type-badge.product { background: rgba(255,255,255,0.3); }
        .type-badge.lens { background: rgba(255,255,255,0.3); }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .timeline::before { left: 20px; }
            .timeline-icon { left: 5px; }
            .timeline-item { padding-left: 70px; }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-clock-history"></i> Stock Movement History
            <small>Track all stock changes</small>
        </h1>
    </section>

    <div class="stock-page" style="padding: 20px;">
        <div class="box box-primary">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px;">
                <h3 class="box-title" style="font-size: 22px; font-weight: 700;">
                    <i class="bi bi-clock-history"></i> Movement History
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-sm"
                       style="color: white; border: 2px solid rgba(255,255,255,0.4); background: rgba(255,255,255,0.1);">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                <div class="stock-summary">
                <span class="type-badge {{ $stock->stockable_type === 'App\\Product' ? 'product' : 'lens' }}">
                    <i class="bi bi-{{ $stock->stockable_type === 'App\\Product' ? 'box' : 'eye' }}"></i>
                    {{ $stock->stockable_type === 'App\\Product' ? 'Product' : 'Lens' }}
                </span>

                    <h3>
                        <i class="bi bi-{{ $stock->stockable_type === 'App\\Product' ? 'box-seam' : 'eyeglasses' }}"></i>
                        {{ $stock->description }}
                    </h3>

                    @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                        <div style="opacity: 0.9; margin-bottom: 15px; position: relative; z-index: 1;">
                            {{ $stock->stockable->brand }} - Index: {{ $stock->stockable->index }}
                        </div>
                    @endif

                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-value">{{ $stock->quantity }}</div>
                            <div class="summary-label">Current Stock</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-value">{{ $stock->total_in }}</div>
                            <div class="summary-label">Total In</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-value">{{ $stock->total_out }}</div>
                            <div class="summary-label">Total Out</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-value">{{ $movements->total() }}</div>
                            <div class="summary-label">Movements</div>
                        </div>
                    </div>
                </div>

                <div class="timeline-container">
                    @if($movements->count() > 0)
                        <div class="timeline">
                            @foreach($movements as $movement)
                                <div class="timeline-item">
                                    <div class="timeline-icon {{ str_replace('_', '-', $movement->type) }}">
                                        <i class="bi bi-{{ $movement->type_info['icon'] }}"></i>
                                    </div>

                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <div>
                                            <span class="timeline-type {{ str_replace('_', '-', $movement->type) }}">
                                                <i class="bi bi-{{ $movement->type_info['icon'] }}"></i>
                                                {{ $movement->type_info['label'] }}
                                            </span>
                                            </div>
                                            <div class="timeline-date">
                                                <i class="bi bi-clock"></i>
                                                {{ $movement->created_at->format('Y-m-d h:i A') }}
                                                <br>
                                                <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>

                                        <div class="timeline-quantity {{ $movement->is_incoming ? 'positive' : 'negative' }}">
                                            {{ $movement->is_incoming ? '+' : '-' }}{{ abs($movement->quantity) }}
                                            <small style="font-size: 16px; opacity: 0.7;">units</small>
                                        </div>

                                        <div class="timeline-details">
                                            <div class="detail-item">
                                                <span class="detail-label">Balance Before</span>
                                                <span class="detail-value">
                                                <span class="balance-badge balance-before">
                                                    {{ $movement->balance_before ?? 0 }}
                                                </span>
                                            </span>
                                            </div>

                                            <div class="detail-item">
                                                <span class="detail-label">Balance After</span>
                                                <span class="detail-value">
                                                <span class="balance-badge balance-after">
                                                    {{ $movement->balance_after ?? 0 }}
                                                </span>
                                            </span>
                                            </div>

                                            @if($movement->cost)
                                                <div class="detail-item">
                                                    <span class="detail-label">Cost</span>
                                                    <span class="detail-value">{{ number_format($movement->cost, 2) }} QAR</span>
                                                </div>
                                            @endif

                                            @if($movement->user)
                                                <div class="detail-item">
                                                    <span class="detail-label">By</span>
                                                    <span class="detail-value">
                                                    <i class="bi bi-person"></i> {{ $movement->user->first_name ?? 'System' }}
                                                </span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($movement->reason)
                                            <div style="margin-top: 15px; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                                                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">
                                                    <i class="bi bi-info-circle"></i> Reason:
                                                </strong>
                                                <p style="margin: 5px 0 0 0; color: #333;">{{ $movement->reason }}</p>
                                            </div>
                                        @endif

                                        @if($movement->notes)
                                            <div style="margin-top: 10px; padding: 12px; background: #fff3cd; border-radius: 8px; border-left: 3px solid #f39c12;">
                                                <strong style="color: #856404; font-size: 12px;">
                                                    <i class="bi bi-sticky"></i> Notes:
                                                </strong>
                                                <p style="margin: 5px 0 0 0; color: #856404;">{{ $movement->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($movements->hasPages())
                            <div class="text-center" style="margin-top: 30px;">
                                {{ $movements->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center" style="padding: 80px 20px;">
                            <i class="bi bi-clock-history" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
                            <h3 style="color: #999; margin-bottom: 15px;">No Movement History</h3>
                            <p style="color: #bbb;">This item has no recorded movements yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
