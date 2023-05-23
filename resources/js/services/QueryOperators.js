
export default function()
{
  return {
    
    get: () => [
        { name: 'equals to', id: '='},
        { name: 'is not equal to', id: '<>'},
        { name: 'contains', id: 'like'},
        { name: 'begins with', id: 'like%'},
        { name: 'ends with', id: '%like'},
        { name: 'is greater than', id: '>'},
        { name: 'is greater than or equal to', id: '>='},
        { name: 'is less than', id: '<'},
        { name: 'is less than or equal to', id: '<='},
      ]
  }
}