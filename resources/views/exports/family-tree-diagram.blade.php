<!DOCTYPE html>
<html>

<head>
    <title>{{ $family->family_name }} - Family Tree</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .family-tree-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .family-level {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 20px;
        }

        .family-member {
            background: white;
            padding: 10px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 150px;
        }

        .family-member-photo {
            width: 130px;
            height: 130px;
            border-radius: 15px;
            overflow: hidden;
            margin: 0 auto 10px;
            background-color: #f8f8f8;
        }

        .family-member-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .family-member-name {
            font-weight: 500;
            margin-bottom: 2px;
            color: #333;
            font-size: 16px;
        }

        .family-member-relation {
            color: #666;
            font-size: 14px;
        }

        .family-connector {
            width: 2px;
            height: 40px;
            background-color: #ccc;
            margin: 0 auto;
            position: relative;
        }

        .family-connector::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            background-color: #ccc;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <h1>Diagram Silsilah {{ $family->family_name }}</h1>

    <div class="family-tree-container">
        @php
            $parents = $familyMembers->filter(function ($member) {
                return $member->relation === 'father' || $member->relation === 'mother';
            });

            $children = $familyMembers->filter(function ($member) {
                return $member->relation === 'child';
            });
        @endphp

        @if ($parents->count() > 0)
            <div class="family-level">
                @foreach ($parents as $parent)
                    <div class="family-member">
                        <div class="family-member-photo">
                            @if ($parent->photo)
                                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $parent->photo))) }}"
                                    alt="{{ $parent->name }}">
                            @else
                                <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/' . ($parent->gender === 'male' ? 'male-avatar.svg' : 'female-avatar.svg')))) }}"
                                    alt="{{ $parent->name }}">
                            @endif
                        </div>
                        <div class="family-member-name">{{ $parent->name }}</div>
                        <div class="family-member-relation">
                            {{ $parent->relation === 'father' ? 'Ayah' : 'Ibu' }}
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($children->count() > 0)
                <div class="family-connector"></div>
            @endif
        @endif

        @if ($children->count() > 0)
            <div class="family-level">
                @foreach ($children as $child)
                    <div class="family-member">
                        <div class="family-member-photo">
                            @if ($child->photo)
                                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $child->photo))) }}"
                                    alt="{{ $child->name }}">
                            @else
                                <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/' . ($child->gender === 'male' ? 'male-avatar.svg' : 'female-avatar.svg')))) }}"
                                    alt="{{ $child->name }}">
                            @endif
                        </div>
                        <div class="family-member-name">{{ $child->name }}</div>
                        <div class="family-member-relation">Anak</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div style="text-align: center; margin-top: 40px; color: #666;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>
