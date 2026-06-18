Dear {{ $filing->proposedBy->name }},
Your filing #{{ $filing->id }} has been canceled.

The reviewer said: {{$filing->deletion_notes}}. 

Thank you for contributing,
The MatronaeDB team
